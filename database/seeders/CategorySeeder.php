<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public array $categories = [
        [
            'id' => 1,
            'name' => 'Cafe',
            'description' => 'Freshly brewed coffee and espresso drinks',
        ],
        [
            'id' => 2,
            'name' => 'Chicken',
            'description' => 'Crispy fried chicken and snacks',
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->categories as $category) {
            Category::updateOrCreate(['id' => $category['id']], $category);
        }
    }
}
