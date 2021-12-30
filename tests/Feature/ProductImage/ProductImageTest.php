<?php

namespace Tests\Feature\ProductImage;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Database\Seeders\PermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductImageTest extends TestCase
{
    use RefreshDatabase;

    public const PRODUCT_IMAGES_PATH = '/productImages';

    protected function setUp(): void
    {
        parent::setUp();
        $this->createUser();
    }

    public function test_new_product_image_can_register(): void
    {
        $product = Product::factory()->create();

        Storage::fake('local');

        $image = $this->post(self::PRODUCT_IMAGES_PATH . '/upload/' . $product->id, [
            'image' => UploadedFile::fake()->image('avatar.jpg'),
        ])->json();

        Storage::disk('local')->assertExists($image['path']);
        $this->assertDatabaseHas('product_images', $image);
    }

    public function test_new_product_image_can_destroy(): void
    {
        Storage::fake('local');
        $productImage = ProductImage::factory()->create();

        $storagePath = 'productsImgs/' . $productImage->product_id . '/images';
        $imageName = 'product-' . time() . '.jpg';
        $productImage->path = $storagePath . '/' . $imageName;
        $productImage->save();

        UploadedFile::fake()->image('avatar.jpg')->storeAs($storagePath, $imageName);

        $this->delete(self::PRODUCT_IMAGES_PATH . '/' . $productImage->id);

        Storage::disk('local')->assertMissing($storagePath . '/' . $imageName);
        $this->assertDatabaseMissing('product_images', $productImage->toArray());
    }

    private function createUser(): User
    {
        $this->seed(PermissionsSeeder::class);
        $user = User::factory()->create();
        $user->assignRole('admin');
        $this->actingAs($user);
        return $user;
    }
}
