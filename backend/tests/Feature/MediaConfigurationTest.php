<?php

namespace Tests\Feature;

use Tests\TestCase;

class MediaConfigurationTest extends TestCase
{
    public function test_media_upload_defaults_are_configured(): void
    {
        $this->assertSame('public', config('media.disk'));
        $this->assertSame('media/library', config('media.paths.library'));
        $this->assertContains('webp', config('media.images.mimes'));
        $this->assertSame(5120, config('media.images.max_size_kb'));
        $this->assertSame(3000, config('media.images.max_width'));
        $this->assertSame(3000, config('media.images.max_height'));
        $this->assertContains('pdf', config('media.files.mimes'));
        $this->assertSame(10240, config('media.files.max_size_kb'));
    }
}
