<?php

namespace App\Console\Commands;

use App\Models\Category;
use Illuminate\Console\Command;

class QueryBuilderPlayground extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:query-builder-playground';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inspect categories and products using Eloquent Query Builder';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $categories = Category::query()->with('products')->get();

        foreach ($categories as $category) {
            $this->info('Category name: '.$category->name);
            $this->line('Total products: '.$category->products->count());

            foreach ($category->products as $product) {
                $this->line('--- Product name: '.$product->name.' ('.$product->formatted_price.')');
            }

            $this->line('--------------------------------------------');
        }
    }
}
