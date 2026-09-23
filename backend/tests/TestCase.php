<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Cache;
use RuntimeException;

/**
 * Every test runs against the dedicated `mr_tee_testing` database (see phpunit.xml),
 * seeded once per run and rolled back after each test, so nothing a test does reaches the
 * development data in `mr_tee`.
 */
abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    /**
     * Seed the catalogue once after the migrations, since many tests read seeded records.
     */
    protected $seed = true;

    private const TEST_DATABASE = 'mr_tee_testing';

    protected function setUp(): void
    {
        parent::setUp();

        // Rate limiter hits live in the cache and would otherwise leak between tests.
        Cache::flush();
    }

    /**
     * RefreshDatabase drops every table, so refuse to run against anything but the
     * dedicated test database.
     */
    protected function beforeRefreshingDatabase(): void
    {
        $database = config('database.connections.'.config('database.default').'.database');

        if ($database !== self::TEST_DATABASE) {
            throw new RuntimeException(
                "Refusing to refresh [{$database}]. Tests must run against [".self::TEST_DATABASE.'], check phpunit.xml.'
            );
        }
    }
}
