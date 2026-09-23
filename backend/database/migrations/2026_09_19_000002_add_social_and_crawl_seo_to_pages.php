<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Share-card fields and crawl controls for each page. Blank share fields fall back to the
     * meta title and description, and a blank image to the site's default share image.
     */
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->string('og_title')->nullable()->after('meta_description');
            $table->text('og_description')->nullable()->after('og_title');
            $table->foreignId('og_image_id')->nullable()->after('og_description')->constrained('media_assets')->nullOnDelete();
            $table->string('canonical_url', 2048)->nullable()->after('og_image_id');
            $table->boolean('is_followable')->default(true)->after('is_indexable');
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropConstrainedForeignId('og_image_id');
            $table->dropColumn(['og_title', 'og_description', 'canonical_url', 'is_followable']);
        });
    }
};
