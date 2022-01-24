<?php

namespace Tests\Feature\Product\API;

use App\Helpers\CurrencyHelper;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use League\Csv\Reader;
use Tests\TestCase;

class ImportTest extends TestCase
{
    use RefreshDatabase;

    public const PRODUCT_PATH = '/api/v1/products';

    protected function setUp(): void
    {
        parent::setUp();
        $this->createUser();
        $this->withoutExceptionHandling();
    }

    public function test_product_can_import(): void
    {
        $importCSV = base_path('Tests/Files/importProducts.csv');

        $file = new UploadedFile($importCSV, 'importProducts.csv', test:true);

        $response = $this->postJson(self::PRODUCT_PATH . '/import', [
            'products' => $file,
        ]);

        $response->assertOk();
        $csv = Reader::createFromPath($importCSV)
        ->setHeaderOffset(0)
        ->setDelimiter(';');

        $products = iterator_to_array($csv->getRecords());

        foreach ($products as $key => $value) {
            unset($products[$key]['weight_unit']);
            $products[$key]['sale_price'] = CurrencyHelper::parseCurrency($products[$key]['sale_price']);
            $this->assertDatabaseHas('products', $products[$key]);
        }
    }

    private function createUser(): User
    {
        $user = User::factory()->create();
        $user->assignRole('admin');
        $this->actingAs($user);
        return $user;
    }
}
