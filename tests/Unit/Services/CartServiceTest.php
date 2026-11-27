<?php

namespace Tests\Unit\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class CartServiceTest extends TestCase
{
    use RefreshDatabase;

    public CartService $cart_service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cart_service = new CartService;
    }

    public function test_get_valid_cart_instance(): void
    {
        $this->assertInstanceOf(Cart::class, $this->cart_service->getCart());
    }

    public function test_get_cart_with_correct_user_id(): void
    {
        $dummy_user_id = 'DUMMY_USER_ID';

        Session::put('user_id', $dummy_user_id);

        $cart = $this->cart_service->getCart();

        $this->assertSame($dummy_user_id, $cart->user_id);
    }

    public function test_should_return_new_cart_current_cart_already_have_order(): void
    {
        $current_cart = $this->cart_service->getCart();

        $current_cart->order()->save(Order::factory()->make());

        $new_cart = $this->cart_service->getCart();

        $this->assertNotSame($current_cart->id, $new_cart->id);
    }

    public function test_get_cart_should_return_same_instance_on_multiple_called_times(): void
    {
        $cart = $this->cart_service->getCart();

        $random_called_times = rand(5, 10);

        $loop = 1;

        do {
            $cart_for_check = $this->cart_service->getCart();
            $loop++;
        } while ($loop < $random_called_times);

        $this->assertSame($cart->id, $cart_for_check->id);
        $this->assertSame($cart->user_id, $cart_for_check->user_id);
    }

    public function test_get_cart_item_by_product_id(): void
    {
        $cart = $this->cart_service->getCart();

        $cart->items()->saveMany(CartItem::factory()->count(3)->make());
        $cart->refresh();

        $cart_item = $cart->items->random();

        $cart_item_for_check = $this->cart_service->getCartItem($cart_item->product_id);

        $this->assertSame($cart_item->id, $cart_item_for_check->id);
        $this->assertSame($cart_item->product_id, $cart_item_for_check->product_id);
    }

    public function test_remove_cart_item_by_product_id(): void
    {
        $cart = $this->cart_service->getCart();

        $cart->items()->saveMany(CartItem::factory()->count(3)->make());
        $cart->refresh();

        $cart_item = $cart->items->random();

        $this->assertSame($cart->items->where('id', $cart_item->id)->first()->id, $cart_item->id);

        $result = $this->cart_service->removeCartItem($cart_item->product_id);

        $this->assertTrue($result);

        $cart->refresh();

        $this->assertNull($cart->items->where('id', $cart_item->id)->first());
        $this->assertFalse($this->cart_service->removeCartItem(-1));
    }
}
