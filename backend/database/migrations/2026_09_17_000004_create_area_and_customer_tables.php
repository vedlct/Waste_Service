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
        Schema::create('coverage_regions', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });

        Schema::create('coverage_areas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coverage_region_id')->constrained('coverage_regions')->cascadeOnDelete();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('postcode_prefix')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['coverage_region_id', 'is_active', 'sort_order'], 'coverage_areas_region_lookup');
            $table->index(['is_featured', 'is_active']);
            $table->index('postcode_prefix');
        });

        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->nullable()->constrained('services')->cascadeOnDelete();
            $table->string('question');
            $table->longText('answer');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['service_id', 'is_active', 'sort_order']);
            $table->index(['is_active', 'sort_order']);
        });

        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->nullable()->constrained('services')->nullOnDelete();
            $table->string('reviewer_name');
            $table->string('reviewer_email')->nullable();
            $table->unsignedTinyInteger('rating');
            $table->longText('body');
            $table->string('source', 80)->default('website');
            $table->string('status', 40)->default('pending');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'published_at']);
            $table->index(['service_id', 'status', 'published_at']);
            $table->index(['rating', 'status']);
            $table->index('reviewer_email');
        });

        Schema::create('contact_enquiries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->nullable()->constrained('services')->nullOnDelete();
            $table->string('name');
            $table->string('email');
            $table->string('phone', 40);
            $table->string('service_label')->nullable();
            $table->longText('message');
            $table->string('status', 40)->default('new');
            $table->string('source', 80)->default('website');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'created_at']);
            $table->index(['service_id', 'status']);
            $table->index('email');
            $table->index('phone');
            $table->index('assigned_to');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_enquiries');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('coverage_areas');
        Schema::dropIfExists('coverage_regions');
    }
};
