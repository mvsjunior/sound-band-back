<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = DB::table('roles')->where('name', 'admin')->value('id');

        User::factory()->create([
            'name' => 'Administrador',
            'email' => 'admin@system.com',
            'role_id' => $adminRole,
            'active' => true,
        ]);
    }
}
