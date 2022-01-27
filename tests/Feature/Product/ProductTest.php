<?php

namespace Tests\Feature\Product;

use App\Helpers\CurrencyHelper;
use App\Models\Product;
use App\Models\User;
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
        $this->withoutExceptionHandling();
    }

    public function test_new_product_can_register(): void
    {
        $product = $this->productProvider()['product'];
        $response = $this->post(self::PRODUCT_PATH, $product);

        $response->assertRedirect(self::PRODUCT_PATH);
        $product['sale_price'] = CurrencyHelper::parseCurrency($product['sale_price']);
        $this->assertDatabaseHas('products', $product);
    }

    public function test_product_information_can_be_updated(): void
    {
        $product = Product::factory()->create();

        $this->put(self::PRODUCT_PATH . '/' . $product->id, [
            'name' => 'Product Update',
            'description' => 'Description Updated',
            'quantity' => 20,
            'price' => 30000,
            'sale_price' => 40000,
        ]);
        $product->refresh();

        $this->assertEquals('Product Update', $product->name);
        $this->assertEquals('Description Updated', $product->description);
        $this->assertEquals(20, $product->quantity);
        $this->assertEquals(30000, $product->price);
        $this->assertEquals(CurrencyHelper::parseCurrency(40000), $product->sale_price);
    }

    public function test_product_can_be_deleted(): void
    {
        $product = Product::factory()->create();

        $this->delete(self::PRODUCT_PATH . '/' . $product->id);

        $this->assertDeleted('products', $product->toArray());
    }

    public function test_product_can_be_disable(): void
    {
        $product = Product::factory()->create();

        $this->put(self::PRODUCT_PATH . '/disable/' . $product->id);

        $this->assertEquals(now(), $product->fresh()->disabled_at);
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
}
