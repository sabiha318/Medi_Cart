<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Pain Relief',
            'Antibiotics',
            'Vitamins & Supplements',
            'Cold & Flu',
            'Skin Care',
            'Baby Care',
            'Diabetes Care',
            'First Aid',
            'Digestive Health',
        ];

        foreach ($categories as $name) {
            Category::updateOrCreate(
                ['name' => $name],
                ['slug' => Str::slug($name)]
            );
        }
    }
}
