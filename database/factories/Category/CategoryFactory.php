<?php

namespace Database\Factories\Category;

use App\Constants\Enum\Status;
use App\Models\Category\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\\Models\\Category\\Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->words(2, true),
            'slug' => $this->faker->slug,
            'status' => $this->faker->randomElement([Status::ACTIVE]),
        ];
    }
}
