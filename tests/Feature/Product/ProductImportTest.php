<?php

namespace Tests\Feature\Product;

use App\Helpers\CurrencyHelper;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use League\Csv\Reader;
use Tests\TestCase;

class ProductImportTest extends TestCase
{
    use RefreshDatabase;

    public const PRODUCT_PATH = '/products';

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

        $response = $this->post(self::PRODUCT_PATH . '/import', [
            'products' => $file,
        ]);

        $response->assertRedirect(self::PRODUCT_PATH);
        $csv = Reader::createFromPath($importCSV)
        ->setHeaderOffset(0)
        ->setDelimiter(';');

        $products = iterator_to_array($csv->getRecords());

        foreach ($products as $key => $value) {
            unset($products[$key]['weight_unit']);
            $this->assertDatabaseHas('products', $products[$key]);
        }
    }

    public function test_product_can_not_import_if_validations_fails(): void
    {
        $importCSV = base_path('Tests/Files/importProductsFail.csv');

        $file = new UploadedFile($importCSV, 'importProducts.csv', test:true);

        $response = $this->post(self::PRODUCT_PATH . '/import', [
            'products' => $file,
        ]);

        $response->assertRedirect(self::PRODUCT_PATH);
        $csv = Reader::createFromPath($importCSV)
        ->setHeaderOffset(0)
        ->setDelimiter(';');

        $products = iterator_to_array($csv->getRecords());

        foreach ($products as $key => $value) {
            unset($products[$key]['weight_unit']);
            $this->assertDatabaseMissing('products', $products[$key]);
        }
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
