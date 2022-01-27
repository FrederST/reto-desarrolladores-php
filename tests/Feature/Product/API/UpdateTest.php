<?php

namespace Tests\Feature\Developers\API;

use App\Helpers\CurrencyHelper;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    public const PRODUCT_PATH = '/api/v1/products';

    protected function setUp(): void
    {
        parent::setUp();
        $this->createUser();
    }

    public function testItUpdatesAProduct(): void
    {
        $data = [
            'name' => 'Product Update',
            'description' => 'Description Updated',
            'quantity' => 20,
            'price' => 30000,
            'sale_price' => 40000,
        ];
        $dbProduct = Product::factory()->create();
        $response = $this->putJson(self::PRODUCT_PATH . '/' . $dbProduct->id, $data);

        $response->assertStatus(Response::HTTP_OK);
        $data['sale_price'] = CurrencyHelper::parseCurrency($data['sale_price']);
        $this->assertDatabaseHas('products', $data);
    }

    private function createUser(): User
    {
        $user = User::factory()->create();
        $user->assignRole('admin');
        $this->actingAs($user);
        return $user;
    }
}
