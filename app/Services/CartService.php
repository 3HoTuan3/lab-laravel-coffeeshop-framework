<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;

class CartService
{
    /**
     * Add new or update existing cart item
     */
    public function addCartItem(array $item): bool
    {
        $cart = $this->getCart();

        $query_condition = [
            'cart_id' => $cart->id,
            'product_id' => $item['product_id'],
        ];

        CartItem::updateOrCreate($query_condition, $item);

        return true;
    }

    /**
     * Remove existing cart item
     */
    public function removeCartItem(int $product_id): bool
    {
        $cart_item = $this->getCartItem($product_id);

        if ($cart_item) {
            $cart_item->forceDelete();

            return true;
        }

        return false;
    }

    public function getCartItem(int $product_id): ?CartItem
    {
        $cart = $this->getCart();

        return $cart->items()->where('product_id', $product_id)->first();
    }

    /**
     * Get cart from session
     */
    public function getCart(): Cart
    {
        $user_id = UserService::getUserIdFromSession();

        $cart = Cart::query()
            ->with('items.product')
            ->where('user_id', $user_id)
            ->doesntHave('order')
            ->first();

        if (! $cart) {
            $cart = Cart::factory()->create([
                'user_id' => $user_id,
            ]);
            $cart->load('items.product');
        }

        return $cart;
    }
}
