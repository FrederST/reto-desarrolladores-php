<?php

namespace Tests\Feature\Order;

use App\Models\Order;
use App\Models\ShoppingCart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\Assert;
use Tests\TestCase;

class ViewTest extends TestCase
{
    use RefreshDatabase;

    public const ORDER_PATH = '/orders';

    public function test_can_render_index_order_page(): void
    {
        $this->createOrder();
        $this->get(self::ORDER_PATH)
            ->assertInertia(
                fn (Assert $page) => $page
                ->component('Order/Index')
                ->has(
                    'orders',
                    fn (Assert $page) => $page
                ->has('data.0.id')
                )
            );
    }

    public function test_can_render_create_order_page(): void
    {
        $shoppingCart = ShoppingCart::factory()->hasShoppingCartItems(3)->create();
        $this->actingAs($shoppingCart->user);
        $this->get(self::ORDER_PATH . '/create')
            ->assertInertia(
                fn (Assert $page) => $page
                ->component('Order/CreateOrEdit')
            );
    }

    private function createOrder(): Order
    {
        $order = Order::factory()->hasOrderItems(3)->create();
        $this->actingAs($order->user);
        return $order;
    }
}
