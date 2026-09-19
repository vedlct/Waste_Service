<?php

namespace Tests\Feature;

use App\Models\MediaAsset;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class MediaLibraryTest extends TestCase
{
    public function test_admin_can_view_media_library_and_data(): void
    {
        $token = Str::uuid()->toString();

        $admin = User::query()->updateOrCreate(
            ['email' => "media-admin-{$token}@example.com"],
            ['name' => 'Media Admin Test', 'password' => 'password', 'role' => 'admin', 'status' => 'active'],
        );

        MediaAsset::query()->updateOrCreate(
            ['disk' => 'public', 'path' => "media/library/test-image-{$token}.jpg"],
            [
                'uploaded_by' => $admin->id,
                'original_name' => "test-image-{$token}.jpg",
                'mime_type' => 'image/jpeg',
                'size_bytes' => 153600,
                'width' => 1200,
                'height' => 800,
                'alt_text' => 'Media test image',
            ],
        );

        $this->actingAs($admin)
            ->get(route('admin.media.index'))
            ->assertOk()
            ->assertSee('Media Library')
            ->assertSee('Manage media assets');

        $this->actingAs($admin)
            ->getJson(route('admin.media.data'))
            ->assertOk()
            ->assertJsonStructure(['draw', 'recordsTotal', 'recordsFiltered', 'data'])
            ->assertSee('media-thumb', false)
            ->assertSee("test-image-{$token}.jpg");
    }

    public function test_admin_can_upload_image_to_media_library(): void
    {
        Storage::fake('public');
        $token = Str::uuid()->toString();

        $admin = User::query()->updateOrCreate(
            ['email' => "media-upload-admin-{$token}@example.com"],
            ['name' => 'Media Upload Admin Test', 'password' => 'password', 'role' => 'admin', 'status' => 'active'],
        );

        $this->actingAs($admin)
            ->get(route('admin.media.create'))
            ->assertOk()
            ->assertSee('Upload Media')
            ->assertSee('Asset details');

        $this->actingAs($admin)
            ->post(route('admin.media.store'), [
                'file' => UploadedFile::fake()->image("clearance-photo-{$token}.jpg", 1200, 800)->size(1200),
                'alt_text' => 'Uploaded clearance photo',
            ])
            ->assertRedirect(route('admin.media.index'))
            ->assertSessionHas('success', 'Media uploaded successfully.');

        $media = MediaAsset::query()
            ->where('uploaded_by', $admin->id)
            ->where('alt_text', 'Uploaded clearance photo')
            ->latest()
            ->firstOrFail();

        $this->assertSame('public', $media->disk);
        $this->assertStringStartsWith('media/library/', $media->path);
        $this->assertSame('image/jpeg', $media->mime_type);
        $this->assertSame(1200, $media->width);
        $this->assertSame(800, $media->height);

        Storage::disk('public')->assertExists($media->path);
    }

    public function test_admin_can_update_media_alt_text_and_metadata(): void
    {
        $token = Str::uuid()->toString();

        $admin = User::query()->updateOrCreate(
            ['email' => "media-edit-admin-{$token}@example.com"],
            ['name' => 'Media Edit Admin Test', 'password' => 'password', 'role' => 'admin', 'status' => 'active'],
        );

        $media = MediaAsset::query()->create([
            'uploaded_by' => $admin->id,
            'disk' => 'public',
            'path' => "media/library/edit-target-{$token}.jpg",
            'original_name' => "edit-target-{$token}.jpg",
            'mime_type' => 'image/jpeg',
            'size_bytes' => 4096,
            'width' => 800,
            'height' => 600,
            'alt_text' => 'Before edit',
            'metadata' => ['legacy_key' => 'kept'],
        ]);

        $this->actingAs($admin)
            ->get(route('admin.media.edit', $media))
            ->assertOk()
            ->assertSee('Before edit')
            ->assertSee('Usage')
            ->assertSee('not referenced by any module', false);

        $this->actingAs($admin)
            ->put(route('admin.media.update', $media), [
                'alt_text' => 'After edit',
                'metadata' => ['title' => 'Edited title', 'caption' => '  ', 'credit' => 'MR. TEE'],
            ])
            ->assertRedirect(route('admin.media.index'))
            ->assertSessionHas('success', 'Media updated successfully.');

        $media->refresh();

        $this->assertSame('After edit', $media->alt_text);
        $this->assertSame('Edited title', $media->metadataValue('title'));
        $this->assertSame('MR. TEE', $media->metadataValue('credit'));
        $this->assertNull($media->metadataValue('caption'));
        $this->assertSame('kept', $media->metadataValue('legacy_key'));

        $media->delete();
    }

    public function test_admin_can_delete_unused_media_and_its_stored_file(): void
    {
        Storage::fake('public');
        $token = Str::uuid()->toString();

        $admin = User::query()->updateOrCreate(
            ['email' => "media-delete-admin-{$token}@example.com"],
            ['name' => 'Media Delete Admin Test', 'password' => 'password', 'role' => 'admin', 'status' => 'active'],
        );

        $this->actingAs($admin)
            ->post(route('admin.media.store'), [
                'file' => UploadedFile::fake()->image("deletable-{$token}.jpg", 600, 400)->size(200),
                'alt_text' => 'Deletable asset',
            ])
            ->assertRedirect(route('admin.media.index'));

        $media = MediaAsset::query()
            ->where('uploaded_by', $admin->id)
            ->where('alt_text', 'Deletable asset')
            ->latest()
            ->firstOrFail();

        Storage::disk('public')->assertExists($media->path);

        $this->actingAs($admin)
            ->delete(route('admin.media.destroy', $media))
            ->assertRedirect(route('admin.media.index'))
            ->assertSessionHas('success', 'Media deleted successfully.');

        Storage::disk('public')->assertMissing($media->path);
        $this->assertDatabaseMissing('media_assets', ['id' => $media->id]);
    }

    public function test_media_in_use_cannot_be_deleted(): void
    {
        $token = Str::uuid()->toString();

        $admin = User::query()->updateOrCreate(
            ['email' => "media-inuse-admin-{$token}@example.com"],
            ['name' => 'Media In Use Admin Test', 'password' => 'password', 'role' => 'admin', 'status' => 'active'],
        );

        $media = MediaAsset::query()->create([
            'uploaded_by' => $admin->id,
            'disk' => 'public',
            'path' => "media/library/in-use-{$token}.jpg",
            'original_name' => "in-use-{$token}.jpg",
            'mime_type' => 'image/jpeg',
            'size_bytes' => 2048,
            'alt_text' => 'Referenced asset',
        ]);

        DB::table('media_assignments')->insert([
            'media_asset_id' => $media->id,
            'mediable_type' => User::class,
            'mediable_id' => $admin->id,
            'role' => 'image',
            'sort_order' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertTrue($media->isInUse());

        $this->actingAs($admin)
            ->from(route('admin.media.index'))
            ->delete(route('admin.media.destroy', $media))
            ->assertRedirect(route('admin.media.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('media_assets', ['id' => $media->id]);

        DB::table('media_assignments')->where('media_asset_id', $media->id)->delete();
        $media->delete();
    }
}
