<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Product::query()
            ->when(request('with'), function (Builder $query, string $with) {
                $query->with(explode(',', $with));
            })
            ->when(request('search'), function (Builder $query, string $search) {
                return $query->where('name', 'like', '%'.$search.'%');
            });

        return $query->simplePaginate();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:products,name|max:255',
            'description' => 'required|max:255',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:20',
            'display_image_url' => 'required|url',
            'category_id' => 'required|exists:categories,id',
        ]);

        return Product::create($validated);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        if (request('with')) {
            $product->loadMissing(explode(',', request('with')));
        }

        return $product;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'required', Rule::unique('products', 'name')->ignore($product->id), 'max:255'],
            'description' => 'sometimes|required|max:255',
            'price' => 'sometimes|required|numeric|min:0',
            'currency' => 'sometimes|required|string|max:20',
            'display_image_url' => 'sometimes|required|url',
            'category_id' => 'sometimes|required|exists:categories,id',
        ]);

        $product->update($validated);

        return $product;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return $product;
    }
}
