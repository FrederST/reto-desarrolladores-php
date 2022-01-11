<?php

namespace Tests\Feature\Customer;

use App\Models\ShoppingCart;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Jetstream\Jetstream;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    public const CUSTOMER_PATH = '/customers';

    public function test_index_screen_can_be_rendered()
    {
        $this->createUser();

        $response = $this->get(self::CUSTOMER_PATH);

        $response->assertStatus(200);
    }

    public function test_new_customer_can_register()
    {
        $this->createUser();

        $response = $this->post(self::CUSTOMER_PATH, [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '3122203321',
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature(),
        ]);

        $response->assertRedirect(self::CUSTOMER_PATH);
        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '3122203321',
        ]);
    }

    public function test_customer_information_can_be_updated()
    {
        $user = $this->createUser();

        $this->put(self::CUSTOMER_PATH . '/' . $user->id, [
            'name' => 'Test Update',
            'email' => 'test@example.com',
            'phone' => '3122203221',
        ]);

        $this->assertEquals('Test Update', $user->fresh()->name);
        $this->assertEquals('test@example.com', $user->fresh()->email);
        $this->assertEquals('3122203221', $user->fresh()->phone);
    }

    public function test_customer_can_be_deactivate()
    {
        $user = $this->createUser();

        $this->put(self::CUSTOMER_PATH . '/disable/' . $user->id);

        $this->assertEquals(now(), $user->fresh()->banned_at);
    }

    public function test_customer_can_be_deleted(): void
    {
        $this->createUser();
        $user = User::factory()->create()->toArray();

        unset($user['profile_photo_url']);
        unset($user['roles']);

        $this->delete(self::CUSTOMER_PATH . '/' . $user['id']);

        $this->assertDeleted('users', $user);
    }

    private function createUser(): User
    {
        $this->actingAs($user = User::factory()->create());
        $user->assignRole('admin');
        $user->shoppingCart()->save(new ShoppingCart(['user_id' => $user->id]));
        return $user;
    }
}
