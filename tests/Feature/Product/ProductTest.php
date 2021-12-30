<?php

namespace Tests\Feature\Product;

use App\Models\Product;
use App\Models\User;
use Database\Seeders\PermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public const PRODUCT_PATH = '/products';

    protected function setUp(): void
    {
        parent::setUp();
        $this->createUser();
    }

    public function test_index_screen_can_be_rendered()
    {
        $response = $this->get(self::PRODUCT_PATH);

        $response->assertStatus(200);
    }

    public function test_new_product_can_register()
    {
        $product = $this->productProvider()['product'];

        $response = $this->post(self::PRODUCT_PATH, $product);

        $response->assertRedirect(self::PRODUCT_PATH);
        $this->assertDatabaseHas('products', $product);
    }

    public function test_product_information_can_be_updated()
    {
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
                'price'=> 80000,
                'sale_price' => 100000,
                'status' => true
            ],
        ];
    }
}
