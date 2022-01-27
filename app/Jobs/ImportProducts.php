<?php

namespace App\Jobs;

use App\Actions\Product\StorageAction;
use App\Actions\Product\UpdateAction;
use App\Constants\ImportStatus;
use App\Constants\WeightUnits;
use App\Models\Product;
use App\Models\User;
use App\Models\WeightUnit;
use App\Notifications\ImportStatusChange;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use League\Csv\Reader;
use Throwable;

class ImportProducts implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private const GTE_RULE = 'gte:0';

    public const RULES = [
        'products' => 'array',
        'products.*.code' => ['string', 'digits_between:1,10'],
        'products.*.name' => ['required', 'string', 'min:1', 'max:100'],
        'products.*.description' => ['required', 'string'],
        'products.*.quantity' => ['required', 'numeric', self::GTE_RULE],
        'products.*.weight' => ['required', 'numeric', self::GTE_RULE],
        'products.*.weight_unit' => ['required'],
        'products.*.price' => ['required', 'numeric', self::GTE_RULE],
        'products.*.sale_price' => ['required', 'numeric', 'gte:products.*.price'],
    ];

    private string $filePath;
    private string $userId;
    private StorageAction $storageAction;
    private UpdateAction $updateAction;

    public function __construct(string $filePath, string $userId)
    {
        $this->filePath = $filePath;
        $this->userId = $userId;
        $this->storageAction = new StorageAction();
        $this->updateAction = new UpdateAction();
    }

    public function handle(): void
    {
        $user = User::find($this->userId);
        $weight_units = Cache::rememberForever('weight_units', function () {
            return WeightUnit::all();
        });

        $csv = Reader::createFromPath(Storage::path($this->filePath))
        ->setHeaderOffset(0)
        ->setDelimiter(';');

        $rules = self::RULES;
        $rules['products.*.weight_unit'] = ['required', Rule::in(WeightUnits::UNITS)];

        $validator = Validator::make(['products' => iterator_to_array($csv->getRecords())], $rules);

        if ($validator->fails()) {
            $user->notify(new ImportStatusChange(ImportStatus::STATUS_FAIL, $validator->errors()->__toString()));
        } else {
            foreach ($csv as $value) {
                $product = $value;
                $product['weight_unit_id'] = $weight_units->where('weight_unit_alias', $product['weight_unit'])->first()->id;
                unset($product['weight_unit']);
                $this->saveProduct($product);
            }
            $user->notify(new ImportStatusChange(ImportStatus::STATUS_SUCCESS, 'All Good'));
        }
    }

    public function failed(Throwable $exception): void
    {
        $user = User::find($this->userId);
        $user->notify(new ImportStatusChange(ImportStatus::STATUS_FAIL, $exception->getMessage()));
    }

    private function saveProduct(array $product): void
    {
        if (isset($product['code']) && $this->storageAction->codeNumberExists($product['code'])) {
            $dbProduct = Product::where('code', $product['code'])->first();
            $this->updateAction->execute($product, $dbProduct);
        } else {
            $this->storageAction->execute($product, new Product());
        }
    }
}
