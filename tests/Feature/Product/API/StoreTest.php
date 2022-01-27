<?php

namespace Tests\Feature\Product\API;

use App\Helpers\CurrencyHelper;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public const PRODUCT_PATH = '/api/v1/products';

    protected function setUp(): void
    {
        parent::setUp();
        $this->createUser();
    }

    public function test_it_store_product(): void
    {
        $product = $this->productProvider()['product'];
        $response = $this->postJson(self::PRODUCT_PATH, $product);

        $response->assertStatus(Response::HTTP_CREATED);
        $product['sale_price'] = CurrencyHelper::parseCurrency($product['sale_price']);
        $this->assertDatabaseHas('products', $product);
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
                'code' => '1',
            ],
        ];
    }
}
