<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'admin@app.local',
            'password' => Hash::make('senha@123')
        ]);

        $this->call([
            RoleSeeder::class,
            MusicianStatusSeeder::class,
            MusicCategorySeeder::class,
            EventTypeSeeder::class,
            ToneSeeder::class,
            SkillSeeder::class,
            UserSeeder::class,
            BandSeeder::class,
            MusicianSeeder::class,
            TagSeeder::class,
            PlaylistSeeder::class,
        ]);
    }
}
