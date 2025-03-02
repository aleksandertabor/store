<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true),
            'description' => $this->faker->text,
            'price' => $this->faker->numberBetween(10000, 50000),
            'stock' => $this->faker->numberBetween(0, 100),
            'rank' => $this->faker->numberBetween(1, 100),
            'image' => "https://prd.place/400?id={$this->faker->numberBetween(1, 45)}",
        ];
    }
}
