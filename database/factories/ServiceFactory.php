<?php

namespace Database\Factories;

use App\Models\CategoryService;
use App\Models\SubCategoryService;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_service_id' => CategoryService::factory(),
            'sub_category_service_id' => SubCategoryService::factory(),
            'title' => $this->faker->sentence(3),
            'price' => $this->faker->numberBetween(1000, 10000), // Price in cents
        ];
    }
}
