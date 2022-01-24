<?php

namespace Tests\Feature\Product\API;

use App\Helpers\CurrencyHelper;
use App\Models\Developer;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    public const PRODUCT_PATH = '/api/v1/products';

    protected function setUp(): void
    {
        parent::setUp();
        $this->createUser();
    }

    public function test_product_can_be_deleted(): void
    {
        $product = Product::factory()->create();

        $this->deleteJson(self::PRODUCT_PATH . '/' . $product->id);

        $this->assertDeleted('products', $product->toArray());
    }

    public function test_product_can_be_disable(): void
    {
        $product = Product::factory()->create();

        $this->putJson(self::PRODUCT_PATH . '/disable/' . $product->id);

        $this->assertEquals(now(), $product->fresh()->disabled_at);
    }

    private function createUser(): User
    {
        $user = User::factory()->create();
        $user->assignRole('admin');
        $this->actingAs($user);
        return $user;
    }
}
