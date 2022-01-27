<?php

namespace Tests\Feature\Product\API;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public const PRODUCT_PATH = '/api/v1/products';

    protected function setUp(): void
    {
        parent::setUp();
        $this->createUser();
    }

    public function test_it_can_list(): void
    {
        $response = $this->getJson(self::PRODUCT_PATH);

        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_response_is_JSON(): void
    {
        $response = $this->getJson(self::PRODUCT_PATH);

        $response->assertHeader('content-type', 'application/json');
    }

    public function test_it_returns_an_array_of_data(): void
    {
        Product::factory()->count(15)->create();
        $response = $this->getJson(self::PRODUCT_PATH);

        $response->assertJson(fn (AssertableJson $json) => $json->has('data')->etc());
    }

    public function test_it_return_product_data(): void
    {
        Product::factory()->create(['code' => 1, 'name' => 'Product 1']);
        Product::factory()->count(3)->create();

        $response = $this->getJson(self::PRODUCT_PATH);

        $response->assertJson(
            fn (AssertableJson $json) => $json->has(
                'data.0',
                fn ($json) => $json->where('name', 'Product 1')
                    ->where('code', sprintf('%010d', 1))
                    ->etc()
            )->etc()
        );
    }

    private function createUser(): User
    {
        $user = User::factory()->create();
        $user->assignRole('admin');
        $this->actingAs($user);
        return $user;
    }
}
