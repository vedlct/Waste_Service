<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;
use Tests\TestCase;

class SeederSafetyTest extends TestCase
{
    public function test_reseeding_never_resets_the_admin_password(): void
    {
        $admin = User::query()->where('email', 'admin@mrtee.local')->firstOrFail();
        $admin->update(['password' => 'a-strong-changed-password']);

        $this->seed(DatabaseSeeder::class);

        $this->assertTrue(Hash::check('a-strong-changed-password', $admin->refresh()->password));
    }

    public function test_seeding_a_populated_production_database_is_refused(): void
    {
        $this->app->detectEnvironment(fn () => 'production');

        try {
            $this->expectException(RuntimeException::class);
            $this->expectExceptionMessage('Refusing to seed a production database');

            // Called directly: the db:seed command would stop at its production prompt first.
            $this->app->make(DatabaseSeeder::class)->run();
        } finally {
            $this->app->detectEnvironment(fn () => 'testing');
        }
    }
}
