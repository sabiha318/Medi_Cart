<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MedicineFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->unique()->words(2, true).' '.$this->faker->numberBetween(100, 999).'mg';

        $unit = $this->faker->randomElement(['strip', 'bottle', 'box', 'others']);

        return [
            'category_id' => Category::inRandomOrder()->first()?->id ?? Category::factory(),
            'name' => ucfirst($name),
            'brand' => $this->faker->company(),
            'slug' => Str::slug($name).'-'.Str::random(6),
            'price' => $this->faker->randomFloat(2, 1, 500),
            'stock' => $this->faker->numberBetween(0, 300),
            'unit' => $unit,
            'tablets_per_strip' => $unit === 'strip' ? $this->faker->randomElement([10, 20, 30]) : null,
            'image' => null,
            'description' => $this->faker->sentence(15),
        ];
    }
}
