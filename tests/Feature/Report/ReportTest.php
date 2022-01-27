<?php

namespace Tests\Feature\Report;

use App\Constants\ReportTypes;
use App\Models\Order;
use App\Models\Product;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    public const REPORT_PATH = '/reports';

    protected function setUp(): void
    {
        parent::setUp();
        $this->createUser();
        $this->withoutExceptionHandling();
    }

    public function test_index_screen_can_be_rendered()
    {
        $response = $this->get(self::REPORT_PATH);

        $response->assertStatus(200);
    }

    public function test_show_screen_can_be_rendered()
    {
        $report = Report::factory()->create();

        $response = $this->get(self::REPORT_PATH . '/' . $report->id);

        $response->assertStatus(200);
        $response->assertSee($report->status);
    }

    public function test_new_order_report_not_can_created_not_found(): void
    {
        $report = $this->reportProvider()['report'];
        $response = $this->post(self::REPORT_PATH, $report);

        $response->assertRedirect(self::REPORT_PATH);
        $this->assertDatabaseCount('reports', 1);
        $this->assertDatabaseHas('reports', $report);
    }

    public function test_new_product_report_not_can_created_not_found(): void
    {
        $report = $this->reportProvider()['report'];
        $report['type'] = ReportTypes::TYPE_PRODUCT;
        $response = $this->post(self::REPORT_PATH, $report);

        $response->assertRedirect(self::REPORT_PATH);
        $this->assertDatabaseCount('reports', 1);
        $this->assertDatabaseHas('reports', $report);
    }

    public function test_new_order_report_can_created(): void
    {
        $report = $this->reportProvider()['report'];
        Order::factory()->hasOrderItems(3)->count(5)->create();
        $response = $this->post(self::REPORT_PATH, $report);

        $response->assertRedirect(self::REPORT_PATH);
        $this->assertDatabaseCount('reports', 1);
        $this->assertDatabaseHas('reports', $report);
        Storage::assertExists(Report::first()->path);
    }

    public function test_new_product_report_can_created(): void
    {
        $report = $this->reportProvider()['report'];
        Product::factory()->count(5)->create();
        $report['type'] = ReportTypes::TYPE_PRODUCT;
        $response = $this->post(self::REPORT_PATH, $report);

        $response->assertRedirect(self::REPORT_PATH);
        $this->assertDatabaseCount('reports', 1);
        $this->assertDatabaseHas('reports', $report);
        Storage::assertExists(Report::first()->path);
    }

    public function test_report_can_be_deleted(): void
    {
        $report = Report::factory()->create();

        $this->delete(self::REPORT_PATH . '/' . $report->id);

        $this->assertDeleted('reports', $report->toArray());
    }

    private function createUser(): User
    {
        $user = User::factory()->create();
        $user->assignRole('admin');
        $this->actingAs($user);
        return $user;
    }

    public function reportProvider(): array
    {
        return [
            'report' => [
                'type' => ReportTypes::TYPE_ORDERS,
            ],
        ];
    }
}
