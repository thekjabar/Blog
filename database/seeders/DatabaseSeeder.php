<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\User;
use App\Models\Post;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        Author::factory(9)->active()->create();
        Author::factory()->inactive()->create();

        // Create 30 posts with random publish status
        Post::factory(30)->create();
    }
}
