<?php

namespace Tests\Feature\Order;

use App\Constants\OrderStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public const ORDER_PATH = '/orders';

    protected function setUp(): void
    {
        parent::setUp();
        $this->createUser();
    }

    public function test_index_screen_can_be_rendered(): void
    {
        $response = $this->get(self::ORDER_PATH);

        $response->assertStatus(200);
    }

    public function testItCanFilter(): void
    {
        $filters = [
            'status' => OrderStatus::STATUS_PENDING,
            'payment_method' => 'place_to_pay',
            'created_at' => now(),
        ];
        Order::factory()->hasOrderItems(3)->count(5)->create();

        $response = $this->get(self::ORDER_PATH. '?' . http_build_query(['filter' => ['order_query' => $filters]]));

        $response->assertStatus(200);
    }


    private function createUser(): User
    {
        $user = User::factory()->create();
        $user->assignRole('admin');
        $this->actingAs($user);
        return $user;
    }

    public function productProvider(): array
    {
        return [
            'product' => [
                'name' => 'New Product',
                'description' => 'New Product Description',
                'quantity' => 8,
                'weight' => 0,
                'weight_unit_id' => 2,
                'price'=> 80000,
                'sale_price' => 100000,
                'disabled_at' => null,
            ],
        ];
    }

    public function filtersProvider(): array
    {
        return [
            'find by name' => ['filter' => ['name' => 'Product 1']],
            'find by description' => ['filter' => ['description' => 'Product 1 Description']],
            'find by sale_price' => ['filter' => ['sale_price' => 2000]],
        ];
    }
}
