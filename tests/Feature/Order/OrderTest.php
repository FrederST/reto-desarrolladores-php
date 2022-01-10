<?php

namespace Tests\Feature\Order;

use App\Constants\OrderStatus;
use App\Models\Order;
use App\Models\ShoppingCart;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public const ORDER_PATH = '/orders';

    protected function setUp(): void
    {
        parent::setUp();
        $this->createUser();
    }

    public function test_can_create_order(): void
    {
        $shoppingCart = ShoppingCart::factory()->hasShoppingCartItems(3)->create();
        $this->actingAs($shoppingCart->user);

        $placeToPayResponse = $this->placeToPayResponse();
        Http::fake([
            'dev.placetopay.com/redirection/api/session' => Http::response($placeToPayResponse, 200),
        ]);

        $order = $this->orderProvider() +
        [
            'payment_process_id' => $placeToPayResponse['requestId'],
            'payment_process_url' => $placeToPayResponse['processUrl'],
        ];

        $response = $this->post(self::ORDER_PATH, $order);

        $response->assertStatus(409);
        $this->assertDatabaseHas('orders', $order);
    }

    public function test_can_check_order_was_is_pending(): void
    {
        $order = $this->initFakeHTTPAndOrder('APPROVED');

        $response = $this->get(self::ORDER_PATH . '/' . $order->id);

        $order->refresh();

        $response->assertStatus(200);
        $this->assertEquals(OrderStatus::STATUS_APPROVED, $order->status);
    }

    public function test_can_check_order_was_is_pending_rejected_result(): void
    {
        $order = $this->initFakeHTTPAndOrder('REJECTED');

        $response = $this->get(self::ORDER_PATH . '/' . $order->id);

        $order->refresh();

        $response->assertStatus(200);
        $this->assertEquals(OrderStatus::STATUS_REJECTED, $order->status);
    }

    public function test_no_check_order_was_is_approved(): void
    {
        $order = $this->initFakeHTTPAndOrder();
        $order->status = OrderStatus::STATUS_APPROVED;
        $order->save();

        $response = $this->get(self::ORDER_PATH . '/' . $order->id);

        $response->assertStatus(200);
        Http::assertNothingSent();
    }

    public function test_can_retry_order_was_is_different_of_approved(): void
    {
        $placeToPayResponse = $this->placeToPayResponse();

        $order = $this->initFakeHTTPAndOrder(url: 'dev.placetopay.com/redirection/api/session');

        $response = $this->get(self::ORDER_PATH . '/retry/' . $order->id);
        $response->assertRedirect($placeToPayResponse['processUrl']);
        $dbOrder = [
            'id' => $order->id,
            'payment_process_id' => $placeToPayResponse['requestId'],
            'payment_process_url' => $placeToPayResponse['processUrl'],
        ];
        $this->assertDatabaseHas('orders', $dbOrder);
    }

    public function test_no_can_retry_order_was_is_approved(): void
    {
        $order = $this->initFakeHTTPAndOrder();
        $order->status = OrderStatus::STATUS_APPROVED;
        $order->save();

        $response = $this->get(self::ORDER_PATH . '/retry/' . $order->id);
        $response->assertRedirect(route('orders.index'));
        Http::assertNothingSent();
    }

    public function test_can_check_no_approved_orders(): void
    {
        $placeToPayResponse = $this->placeToPayResponse('APPROVED');
        Http::fake([
            'dev.placetopay.com/redirection/api/session/*' => Http::response($placeToPayResponse, 200),
        ]);

        Order::factory()->hasOrderItems(3)->count(5)->create();
        $this->travel(8)->minutes();

        $this->artisan('check:orders')->assertExitCode(0);

        Http::assertSentCount(5);
    }

    private function createUser(): User
    {
        $user = User::factory()->create();
        $user->assignRole('admin');
        $this->actingAs($user);
        return $user;
    }

    private function initFakeHTTPAndOrder($status = 'OK', $url = 'dev.placetopay.com/redirection/api/session/*'): Order
    {
        $placeToPayResponse = $this->placeToPayResponse($status);
        Http::fake([
            $url => Http::response($placeToPayResponse, 200),
        ]);

        $order = Order::factory()->hasOrderItems(3)->create();
        $this->actingAs($order->user);
        return $order;
    }

    public function orderProvider(): array
    {
        return [
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'address' => 'Km 1',
            'country_id' => 187,
            'city_id' => 761,
            'post_code' => 12341,
            'phone_number' => 3120033321,
            'notes' => 'Some note',
        ];
    }

    public function placeToPayResponse($status = 'OK'): array
    {
        return [
            'status' => [
                'status'=> $status,
                'reason'=> 'PC',
                'message'=> 'La petición se ha procesado correctamente',
                'date'=> '2021-11-30T15:08:27-05:00',
            ],
            'requestId'=> 1,
            'processUrl'=> 'https://checkout-co.placetopay.com/session/1/cc9b8690b1f7228c78b759ce27d7e80a',
        ];
    }
}
