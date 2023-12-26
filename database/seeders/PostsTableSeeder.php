<?php

namespace Database\Seeders;

use App\Models\Post\Post;
use Illuminate\Database\Seeder;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Post::factory(30)->create();
    }
}
