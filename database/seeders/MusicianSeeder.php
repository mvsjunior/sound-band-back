<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MusicianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $status = DB::table('musician_statuses')
            ->where('name', 'active')
            ->value('id');

        DB::table('musicians')->insertOrIgnore([
            [
                'name' => 'João Vocal',
                'email' => 'joao@louvor.com',
                'status_id' => $status,
            ],
            [
                'name' => 'Maria Teclado',
                'email' => 'maria@louvor.com',
                'status_id' => $status,
            ],
        ]);
    }
}
