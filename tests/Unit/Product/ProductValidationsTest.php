<?php

namespace Tests\Unit\Product;

use App\Models\User;
use Database\Seeders\PermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductValidationsTest extends TestCase
{
    use RefreshDatabase;

    public const PRODUCT_PATH = '/products';

    protected function setUp(): void
    {
        parent::setUp();
        $this->createUser();
    }

    public function test_it_can_valid_required_values_in_store(): void
    {
        $response = $this->post(self::PRODUCT_PATH, []);

        $response->assertSessionHasErrors([
            'name' => 'The name field is required.',
            'description' => 'The description field is required.',
            'quantity' => 'The quantity field is required.',
            'weight' => 'The weight field is required.',
            'weight_unit_id' => 'The weight unit id field is required.',
            'price' => 'The price field is required.',
            'sale_price' => 'The sale price field is required.',
            'currency_id' => 'The currency id field is required.',
            'status' => 'The status field is required.',
        ]);

        $this->assertDatabaseCount('products', 0);
    }

    public function test_not_can_create_if_weigh_no_exits(): void
    {
        $product = $this->productProvider()['product'];
        $product['weight_unit_id'] = 0;
        $response = $this->post(self::PRODUCT_PATH, $product);

        $response->assertSessionHasErrors([
            'weight_unit_id' => 'The selected weight unit id is invalid.',
        ]);

        $this->assertDatabaseMissing('products', $product);
    }

    public function test_not_can_create_if_currency_no_exits(): void
    {
        $product = $this->productProvider()['product'];
        $product['currency_id'] = 0;
        $response = $this->post(self::PRODUCT_PATH, $product);

        $response->assertSessionHasErrors([
            'currency_id' => 'The selected currency id is invalid.',
        ]);

        $this->assertDatabaseMissing('products', $product);
    }

    public function test_it_can_valid_sale_price_gte_of_price_in_store(): void
    {
        $product = $this->productProvider()['product'];
        $product['sale_price'] = 0;
        $response = $this->post(self::PRODUCT_PATH, $product);

        $response->assertSessionHasErrors([
            'sale_price' => 'The sale price must be greater than or equal to 80000.',
        ]);

        $this->assertDatabaseMissing('products', $product);
    }

    public function test_it_can_valid_quantity_weight_price_gte_of_0_in_store(): void
    {
        $product = $this->productProvider()['product'];
        $product['quantity'] = -1;
        $product['weight'] = -1;
        $product['price'] = -1;
        $response = $this->post(self::PRODUCT_PATH, $product);

        $response->assertSessionHasErrors([
            'quantity' => 'The quantity must be greater than or equal to 0.',
            'weight' => 'The weight must be greater than or equal to 0.',
            'price' => 'The price must be greater than or equal to 0.',
        ]);

        $this->assertDatabaseMissing('products', $product);
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
                'weight_unit_id' => 0,
                'price' => 80000,
                'sale_price' => 100000,
                'currency_id' => 1,
                'status' => true,
            ],
        ];
    }
}
