<?php

namespace Database\Factories\Post;

use App\Constants\Enum\Status;
use App\Models\Post\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'slug' => $this->faker->slug,
            'is_featured' => $this->faker->randomElement(['featured', 'normal']),
            'image' => $this->faker->imageUrl,
            'excerpt' => $this->faker->text,
            'content' => $this->faker->paragraph,
            'posted_at' => $this->faker->dateTime,
            'status' => $this->faker->randomElement([Status::ACTIVE]),
        ];
    }
}
