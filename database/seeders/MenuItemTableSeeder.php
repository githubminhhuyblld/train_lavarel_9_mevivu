<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MenuItemTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menuId = DB::table('menus')->where('name', 'Main Menu')->first()->id;

        DB::table('menu_items')->insert([
            [
                'title' => 'Home',
                'slug' => Str::slug('Home'),
                'menu_id' => $menuId,
                'order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'About Us',
                'slug' => Str::slug('About Us'),
                'menu_id' => $menuId,
                'order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Services',
                'slug' => Str::slug('Services'),
                'menu_id' => $menuId,
                'order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Contact',
                'slug' => Str::slug('Contact'),
                'menu_id' => $menuId,
                'order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
