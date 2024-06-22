<?php

namespace Database\Seeders;

use App\Enums\Region;
use App\Models\Conference;
use App\Models\Talk;
use App\Models\User;
use App\Models\Venue;
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
            'name' => 'Alin Haidau',
            'email' => 'alin78hai@gmail.com',
            'password' => bcrypt('secret'),
        ]);

        Venue::factory(5)->create();
        // SpeakerFactory::factory(10)->create();
        Talk::factory(10)->create();
        Conference::factory(3)->create();
    }
}
