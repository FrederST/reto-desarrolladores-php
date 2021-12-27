<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Database\Seeders\PermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public const PRODUCT_PATH = '/products';

    public function test_new_product_can_register()
    {
        $this->createUser();
        $product = $this->productProvider()['product'];

        $response = $this->post(self::PRODUCT_PATH, $product);

        $response->assertRedirect(self::PRODUCT_PATH);
        $this->assertDatabaseHas('products', $product);
    }

    public function test_product_information_can_be_updated()
    {
        $this->createUser();
        $product = Product::factory()->create();

        $this->put(self::PRODUCT_PATH . '/' . $product->id, [
            'name' => 'Product Update',
            'description' => 'Description Updated',
            'quantity' => 20,
        ]);

        $this->assertEquals('Product Update', $product->fresh()->name);
        $this->assertEquals('Description Updated', $product->fresh()->description);
        $this->assertEquals(20, $product->fresh()->quantity);
    }

    public function test_product_can_be_deleted()
    {
        $product = Product::factory()->create();

        $this->delete(self::PRODUCT_PATH . '/' . $product->id);

        $this->assertDeleted('products', $product->toArray());
    }

    private function createUser(): User
    {
        $this->seed(PermissionsSeeder::class);
        $this->actingAs($user = User::factory()->create());
        $user->assignRole('admin');
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
                'price'=> 8.000,
                'sale_price' => 10.000,
            ],
        ];
    }
}
