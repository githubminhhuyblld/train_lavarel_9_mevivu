<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('menus')->insert([
            'name' => 'Main Menu',
            'status' => 1,
            'menu_font' => 'Arial',
            'menu_color' => '#000000',
            'background' => '#ffffff',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
