<?php

namespace Tests\Feature;

use App\Models\MediaAsset;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class MediaLibraryTest extends TestCase
{
    private function useMysqlDatabase(): void
    {
        config([
            'database.default' => 'mysql',
            'database.connections.mysql.database' => 'mr_tee',
        ]);
    }

    public function test_admin_can_view_media_library_and_data(): void
    {
        $this->useMysqlDatabase();
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
        $this->useMysqlDatabase();
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
}
