<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

final class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Roast Beef Tenderloin',
                'slug' => 'roast-beef-tenderloin',
                'description' => 'Premium beef tenderloin roasted to perfection, served with seasonal vegetables and a rich red wine reduction sauce.',
                'price' => 32.99,
                'stock_quantity' => 15,
                'image' => '/images/products/roast-beef-tenderloin.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Roasted Grape & Goat Cheese Salad',
                'slug' => 'roasted-grape-goat-cheese-salad',
                'description' => 'A delightful salad featuring caramelized roasted grapes, creamy goat cheese, mixed greens, and candied walnuts with balsamic vinaigrette.',
                'price' => 16.99,
                'stock_quantity' => 25,
                'image' => '/images/products/roasted-grape-goat-cheese-salad.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Roasted Brussels Sprouts',
                'slug' => 'roasted-brussels-sprouts',
                'description' => 'Crispy roasted Brussels sprouts with garlic, olive oil, and a touch of balsamic glaze. A perfect side dish.',
                'price' => 12.99,
                'stock_quantity' => 30,
                'image' => '/images/products/roasted-brussels-sprouts.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Crispy Roasted Potatoes',
                'slug' => 'crispy-roasted-potatoes',
                'description' => 'Golden crispy potatoes roasted with herbs, garlic, and olive oil. Perfectly seasoned and irresistibly crunchy.',
                'price' => 11.99,
                'stock_quantity' => 35,
                'image' => '/images/products/crispy-roasted-potatoes.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Creamy Mac & Cheese',
                'slug' => 'creamy-mac-cheese',
                'description' => 'Classic comfort food made with a blend of premium cheeses, perfectly cooked pasta, and a crispy breadcrumb topping.',
                'price' => 14.99,
                'stock_quantity' => 40,
                'image' => '/images/products/creamy-mac-cheese.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Apple Crumb Pie',
                'slug' => 'apple-crumb-pie',
                'description' => 'Homestyle apple pie with a buttery crumb topping, filled with cinnamon-spiced apples and a flaky crust.',
                'price' => 18.99,
                'stock_quantity' => 20,
                'image' => '/images/products/apple-crumb-pie.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Challah Dinner Rolls',
                'slug' => 'challah-dinner-rolls',
                'description' => 'Soft and fluffy challah bread rolls, perfect for any meal. Slightly sweet with a beautiful golden crust.',
                'price' => 9.99,
                'stock_quantity' => 45,
                'image' => '/images/products/challah-dinner-rolls.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Classic Crab Cakes & Tartar Sauce',
                'slug' => 'classic-crab-cakes-tartar-sauce',
                'description' => 'Premium lump crab meat formed into golden cakes, pan-seared and served with house-made tartar sauce.',
                'price' => 28.99,
                'stock_quantity' => 12,
                'image' => '/images/products/classic-crab-cakes.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Mushroom & Goat Cheese Tart',
                'slug' => 'mushroom-goat-cheese-tart',
                'description' => 'Flaky puff pastry filled with sautéed wild mushrooms, creamy goat cheese, and fresh thyme.',
                'price' => 22.99,
                'stock_quantity' => 18,
                'image' => '/images/products/mushroom-goat-cheese-tart.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Pomegranate-Cherry Glazed Chicken',
                'slug' => 'pomegranate-cherry-glazed-chicken',
                'description' => 'Tender chicken breast glazed with a sweet and tangy pomegranate-cherry reduction, served with roasted vegetables.',
                'price' => 24.99,
                'stock_quantity' => 22,
                'image' => '/images/products/pomegranate-cherry-glazed-chicken.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Thai-Style Sweet Chili Glazed Salmon',
                'slug' => 'thai-sweet-chili-glazed-salmon',
                'description' => 'Fresh salmon fillet glazed with Thai sweet chili sauce, served over jasmine rice with crispy vegetables.',
                'price' => 27.99,
                'stock_quantity' => 16,
                'image' => '/images/products/thai-sweet-chili-salmon.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Prosciutto & Mozzarella Sandwiches',
                'slug' => 'prosciutto-mozzarella-sandwiches',
                'description' => 'Italian-style sandwiches with thinly sliced prosciutto, fresh mozzarella, tomatoes, and basil on crusty ciabatta.',
                'price' => 19.99,
                'stock_quantity' => 28,
                'image' => '/images/products/prosciutto-mozzarella-sandwiches.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Caribbean Jerk Pork Chops',
                'slug' => 'caribbean-jerk-pork-chops',
                'description' => 'Bone-in pork chops marinated in authentic Caribbean jerk seasoning, grilled and served with tropical rice.',
                'price' => 25.99,
                'stock_quantity' => 4,
                'image' => '/images/products/caribbean-jerk-pork-chops.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Chicken Satay Rice Bowls',
                'slug' => 'chicken-satay-rice-bowls',
                'description' => 'Grilled chicken skewers with creamy peanut satay sauce, served over coconut rice with fresh vegetables.',
                'price' => 21.99,
                'stock_quantity' => 26,
                'image' => '/images/products/chicken-satay-rice-bowls.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Saucy Italian Chicken & Focaccia Subs',
                'slug' => 'saucy-italian-chicken-focaccia-subs',
                'description' => 'Tender Italian-seasoned chicken smothered in marinara, topped with melted provolone on fresh focaccia bread.',
                'price' => 20.99,
                'stock_quantity' => 24,
                'image' => '/images/products/saucy-italian-chicken-subs.jpg',
                'is_active' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(
                ['slug' => $product['slug']],
                $product
            );
        }
    }
}
