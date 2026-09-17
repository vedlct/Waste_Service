<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('media_assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('disk', 60)->default('public');
            $table->string('path');
            $table->string('original_name')->nullable();
            $table->string('mime_type', 120)->nullable();
            $table->unsignedBigInteger('size_bytes')->nullable();
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->string('alt_text')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['disk', 'path']);
            $table->index('mime_type');
            $table->index('uploaded_by');
        });

        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('group', 80)->default('general');
            $table->string('key', 120);
            $table->string('type', 40)->default('string');
            $table->json('value')->nullable();
            $table->string('label')->nullable();
            $table->text('help_text')->nullable();
            $table->boolean('is_public')->default(true);
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['group', 'key']);
            $table->index(['is_public', 'group']);
            $table->index('updated_by');
        });

        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('pages')->nullOnDelete();
            $table->foreignId('hero_media_id')->nullable()->constrained('media_assets')->nullOnDelete();
            $table->string('slug')->unique();
            $table->string('route_path')->unique();
            $table->string('title');
            $table->string('navigation_label')->nullable();
            $table->string('template', 80)->default('default');
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('status', 40)->default('draft');
            $table->boolean('is_indexable')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'published_at']);
            $table->index(['parent_id', 'sort_order']);
            $table->index(['template', 'status']);
            $table->index('hero_media_id');
        });

        Schema::create('page_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained('pages')->cascadeOnDelete();
            $table->foreignId('media_id')->nullable()->constrained('media_assets')->nullOnDelete();
            $table->string('section_key', 120);
            $table->string('component', 120);
            $table->string('eyebrow')->nullable();
            $table->string('heading')->nullable();
            $table->string('subheading')->nullable();
            $table->longText('body')->nullable();
            $table->json('settings')->nullable();
            $table->boolean('is_enabled')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['page_id', 'section_key']);
            $table->index(['page_id', 'is_enabled', 'sort_order']);
            $table->index('component');
            $table->index('media_id');
        });

        Schema::create('section_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_section_id')->constrained('page_sections')->cascadeOnDelete();
            $table->foreignId('media_id')->nullable()->constrained('media_assets')->nullOnDelete();
            $table->string('item_key', 120)->nullable();
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->longText('body')->nullable();
            $table->string('url')->nullable();
            $table->string('icon')->nullable();
            $table->json('settings')->nullable();
            $table->boolean('is_enabled')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['page_section_id', 'is_enabled', 'sort_order']);
            $table->index('media_id');
        });

        Schema::create('media_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('media_asset_id')->constrained('media_assets')->cascadeOnDelete();
            $table->morphs('mediable');
            $table->string('role', 80)->default('image');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['media_asset_id', 'mediable_type', 'mediable_id', 'role'], 'media_assignments_unique_role');
            $table->index(['mediable_type', 'mediable_id', 'role', 'sort_order'], 'media_assignments_lookup_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_assignments');
        Schema::dropIfExists('section_items');
        Schema::dropIfExists('page_sections');
        Schema::dropIfExists('pages');
        Schema::dropIfExists('site_settings');
        Schema::dropIfExists('media_assets');
    }
};
