<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Medicine;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MedicineSeeder extends Seeder
{
    public function run(): void
    {
        $medicines = [
            ['Paracetamol 500mg', 'Square', 'Pain Relief', 2.50, 500, 'strip'],
            ['Ibuprofen 400mg', 'Beximco', 'Pain Relief', 3.20, 300, 'strip'],
            ['Amoxicillin 250mg', 'ACI', 'Antibiotics', 8.00, 150, 'strip'],
            ['Azithromycin 500mg', 'Incepta', 'Antibiotics', 12.50, 100, 'strip'],
            ['Vitamin C 1000mg', 'Renata', 'Vitamins & Supplements', 6.75, 250, 'bottle'],
            ['Multivitamin Syrup', 'Square', 'Vitamins & Supplements', 95.00, 60, 'bottle'],
            ['Cetirizine 10mg', 'Opsonin', 'Cold & Flu', 15.80, 400, 'strip'],
            ['Cough Syrup 100ml', 'ACI', 'Cold & Flu', 45.00, 80, 'bottle'],
            ['Antiseptic Cream', 'Square', 'Skin Care', 55.00, 120, 'box'],
            ['Baby Diaper Rash Cream', 'Johnson', 'Baby Care', 180.00, 40, 'box'],
            ['Insulin Glargine', 'Novo Nordisk', 'Diabetes Care', 650.00, 25, 'box'],
            ['Blood Glucose Strips', 'ACCU-CHEK', 'Diabetes Care', 950.00, 15, 'box'],
            ['Adhesive Bandages', 'Band-Aid', 'First Aid', 40.00, 200, 'box'],
            ['Antiseptic Solution 250ml', 'Dettol', 'First Aid', 120.00, 90, 'bottle'],
        ];

        foreach ($medicines as [$name, $brand, $categoryName, $price, $stock, $unit]) {
            $category = Category::where('name', $categoryName)->first();

            if (! $category) {
                continue;
            }

            Medicine::updateOrCreate(
                ['name' => $name, 'brand' => $brand],
                [
                    'category_id' => $category->id,
                    'slug' => Str::slug($name).'-'.Str::random(6),
                    'price' => $price,
                    'stock' => $stock,
                    'unit' => $unit,
                    'tablets_per_strip' => $unit === 'strip' ? 10 : null,
                    'description' => "$name by $brand — a trusted product in the {$categoryName} category.",
                ]
            );
        }
    }
}
