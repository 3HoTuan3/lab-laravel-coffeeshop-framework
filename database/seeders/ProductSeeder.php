<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public const DEFAULT_CURRENCY = 'VNĐ';

    public array $products = [
        [
            'id' => 1,
            'category_id' => 1,
            'name' => 'Cappuccino',
            'description' => 'Cappuccino is a coffee drink composed of a single espresso shot and hot steamed milk.',
            'price' => 35000,
            'display_image_url' => 'https://images.unsplash.com/photo-1509042239860-f550ce710b93',
        ],
        [
            'id' => 2,
            'category_id' => 1,
            'name' => 'Caramel Macchiato',
            'description' => 'Espresso combined with vanilla-flavored syrup, steamed milk, and caramel drizzle.',
            'price' => 45000,
            'display_image_url' => 'https://images.unsplash.com/photo-1485808191679-5f86510681a2',
        ],
        [
            'id' => 3,
            'category_id' => 1,
            'name' => 'Iced Americano',
            'description' => 'Rich espresso shots topped with cold water and ice for a crisp finish.',
            'price' => 30000,
            'display_image_url' => 'https://images.unsplash.com/photo-1517701550927-30cf4ba1dba5',
        ],
        [
            'id' => 4,
            'category_id' => 1,
            'name' => 'Matcha Latte',
            'description' => 'Smooth and creamy matcha sweetened just right and served with steamed milk.',
            'price' => 49000,
            'display_image_url' => 'https://images.unsplash.com/photo-1515823064-d6e0c04616a7',
        ],
        [
            'id' => 5,
            'category_id' => 1,
            'name' => 'Vietnamese Iced Coffee',
            'description' => 'Traditional dark roast coffee brewed with a phin and sweetened condensed milk.',
            'price' => 29000,
            'display_image_url' => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd',
        ],
        [
            'id' => 6,
            'category_id' => 2,
            'name' => 'Crispy Fried Chicken',
            'description' => 'Golden, crunchy fried chicken seasoned with signature herbs and spices.',
            'price' => 55000,
            'display_image_url' => 'https://images.unsplash.com/photo-1626082927389-6cd097cdc6ec',
        ],
        [
            'id' => 7,
            'category_id' => 2,
            'name' => 'Spicy Chicken Wings',
            'description' => 'Juicy chicken wings glazed in a bold and spicy house sauce.',
            'price' => 65000,
            'display_image_url' => 'https://images.unsplash.com/photo-1567620832903-9fc6debc209f',
        ],
        [
            'id' => 8,
            'category_id' => 2,
            'name' => 'Chicken Popcorn',
            'description' => 'Bite-sized crispy chicken pieces, perfect as a snack with your favorite drink.',
            'price' => 45000,
            'display_image_url' => 'https://images.unsplash.com/photo-1562967914-608f82629710',
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->products as $product) {
            Product::updateOrCreate(
                ['id' => $product['id']],
                [
                    ...$product,
                    'currency' => self::DEFAULT_CURRENCY,
                ]
            );
        }
    }
}
