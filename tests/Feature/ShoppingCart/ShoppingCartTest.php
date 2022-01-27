<?php

namespace Tests\Feature\ShoppingCart;

use App\Models\Product;
use App\Models\ShoppingCart;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShoppingCartTest extends TestCase
{
    use RefreshDatabase;

    public const SHOPPING_CART_PATH = '/shoppingCartItems';

    protected function setUp(): void
    {
        parent::setUp();
        $this->createUser();
        $this->withoutExceptionHandling();
    }

    public function test_index_screen_can_be_rendered()
    {
        $this->createUser();

        $response = $this->get(self::SHOPPING_CART_PATH);

        $response->assertStatus(200);
    }

    public function test_product_can_add_to_cart()
    {
        $user = $this->createUser();
        $product = Product::factory()->create();

        $data = [
            'product_id' => $product->id,
            'quantity' => 2,
            'shopping_cart_id' => $user->shoppingCart->id,
        ];

        $response = $this->post(self::SHOPPING_CART_PATH, $data);
        $response->assertRedirect(self::SHOPPING_CART_PATH);

        $this->assertDatabaseHas('shopping_cart_items', $data);
    }

    public function test_product_can_remove_to_cart()
    {
        $shoppingCart = ShoppingCart::factory()->hasShoppingCartItems(3)->create();
        $this->actingAs($shoppingCart->user);

        $itemForDelete = $shoppingCart->shoppingCartItems()->first()->toArray();

        $response = $this->delete(self::SHOPPING_CART_PATH . '/' . $itemForDelete['id']);
        $response->assertRedirect(self::SHOPPING_CART_PATH);

        $this->assertDatabaseMissing('shopping_cart_items', $itemForDelete);
    }

    public function test_product_is_add_to_current_cart_item_if_exits()
    {
        $user = $this->createUser();
        $product = Product::factory()->create();

        $data = [
            'product_id' => $product->id,
            'quantity' => 2,
            'shopping_cart_id' => $user->shoppingCart->id,
        ];

        $this->post(self::SHOPPING_CART_PATH, $data);
        $response = $this->post(self::SHOPPING_CART_PATH, $data);
        $response->assertRedirect(self::SHOPPING_CART_PATH);

        $data['quantity'] = 4;

        $this->assertDatabaseHas('shopping_cart_items', $data);
    }

    private function createUser(): User
    {
        $this->actingAs($user = User::factory()->create());
        $user->assignRole('admin');
        $user->shoppingCart()->save(new ShoppingCart(['user_id' => $user->id]));
        return $user;
    }
}
