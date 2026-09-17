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
        Schema::create('service_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('service_categories')->nullOnDelete();
            $table->string('slug')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['parent_id', 'is_active', 'sort_order']);
        });

        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_category_id')->nullable()->constrained('service_categories')->nullOnDelete();
            $table->foreignId('page_id')->nullable()->constrained('pages')->nullOnDelete();
            $table->foreignId('hero_media_id')->nullable()->constrained('media_assets')->nullOnDelete();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('short_name')->nullable();
            $table->string('route_path')->unique();
            $table->string('headline')->nullable();
            $table->text('summary')->nullable();
            $table->longText('description')->nullable();
            $table->string('status', 40)->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_bookable')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['service_category_id', 'status', 'sort_order']);
            $table->index(['is_featured', 'status', 'sort_order']);
            $table->index(['is_bookable', 'status']);
            $table->index('page_id');
            $table->index('hero_media_id');
        });

        Schema::create('service_relations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();
            $table->foreignId('related_service_id')->constrained('services')->cascadeOnDelete();
            $table->string('relation_type', 80)->default('related');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['service_id', 'related_service_id', 'relation_type'], 'service_relations_unique');
            $table->index(['service_id', 'relation_type', 'sort_order']);
        });

        Schema::create('service_content_blocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();
            $table->foreignId('media_id')->nullable()->constrained('media_assets')->nullOnDelete();
            $table->string('block_key', 120);
            $table->string('component', 120)->default('content_block');
            $table->string('eyebrow')->nullable();
            $table->string('heading')->nullable();
            $table->string('subheading')->nullable();
            $table->longText('body')->nullable();
            $table->json('settings')->nullable();
            $table->boolean('is_enabled')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['service_id', 'block_key']);
            $table->index(['service_id', 'is_enabled', 'sort_order']);
            $table->index('component');
            $table->index('media_id');
        });

        Schema::create('service_block_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_content_block_id')->constrained('service_content_blocks')->cascadeOnDelete();
            $table->foreignId('media_id')->nullable()->constrained('media_assets')->nullOnDelete();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->longText('body')->nullable();
            $table->string('icon')->nullable();
            $table->string('url')->nullable();
            $table->json('settings')->nullable();
            $table->boolean('is_enabled')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['service_content_block_id', 'is_enabled', 'sort_order'], 'service_block_items_lookup');
            $table->index('media_id');
        });

        Schema::create('price_categories', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('eyebrow')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('image_id')->nullable()->constrained('media_assets')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
            $table->index('image_id');
        });

        Schema::create('service_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('price_category_id')->constrained('price_categories')->cascadeOnDelete();
            $table->foreignId('image_id')->nullable()->constrained('media_assets')->nullOnDelete();
            $table->string('sku')->unique();
            $table->string('slug')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedInteger('price_pence');
            $table->unsignedInteger('vat_rate_basis_points')->default(2000);
            $table->string('pricing_status', 40)->default('placeholder');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['price_category_id', 'is_active', 'sort_order']);
            $table->index(['pricing_status', 'is_active']);
            $table->index('image_id');
        });

        Schema::create('load_packages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->unsignedInteger('price_inc_vat_pence');
            $table->unsignedInteger('price_ex_vat_pence')->nullable();
            $table->unsignedInteger('vat_rate_basis_points')->default(2000);
            $table->unsignedInteger('max_weight_kg')->nullable();
            $table->decimal('volume_cubic_yards', 8, 2)->nullable();
            $table->unsignedInteger('sack_equivalent')->nullable();
            $table->unsignedInteger('loading_time_minutes')->nullable();
            $table->string('pricing_status', 40)->default('placeholder');
            $table->boolean('is_popular')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_active', 'sort_order']);
            $table->index(['pricing_status', 'is_active']);
            $table->index('is_popular');
        });

        Schema::create('extra_charges', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedInteger('amount_pence')->nullable();
            $table->boolean('is_variable')->default(false);
            $table->string('charge_type', 60)->default('fixed');
            $table->string('pricing_status', 40)->default('active');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
            $table->index(['charge_type', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('extra_charges');
        Schema::dropIfExists('load_packages');
        Schema::dropIfExists('service_items');
        Schema::dropIfExists('price_categories');
        Schema::dropIfExists('service_block_items');
        Schema::dropIfExists('service_content_blocks');
        Schema::dropIfExists('service_relations');
        Schema::dropIfExists('services');
        Schema::dropIfExists('service_categories');
    }
};
