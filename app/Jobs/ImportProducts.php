<?php

namespace App\Jobs;

use App\Helpers\CurrencyHelper;
use App\Http\Requests\Product\StoreRequest;
use App\Models\Product;
use App\Models\WeightUnit;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use League\Csv\Reader;

class ImportProducts implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private const GTE_RULE = 'gte:0';

    public const RULES = [
        'products' => 'array',
        'products.*.name' => ['required', 'string', 'min:1', 'max:100'],
        'products.*.description' => ['required', 'string'],
        'products.*.quantity' => ['required', 'numeric', self::GTE_RULE],
        'products.*.weight' => ['required', 'numeric', self::GTE_RULE],
        'products.*.weight_unit' => ['required'],
        'products.*.price' => ['required', 'numeric', self::GTE_RULE],
        'products.*.sale_price' => ['required', 'numeric'],
    ];

    private string $file_path;

    public function __construct(string $file_path)
    {
        $this->file_path = $file_path;
    }

    public function handle(): void
    {
        $csv = Reader::createFromPath(Storage::path($this->file_path))
        ->setHeaderOffset(0)
        ->setDelimiter(';');

        foreach ($csv as $value) {
            $product = $value;
            $product['weight_unit_id'] = WeightUnit::where('weight_unit_alias', $product['weight_unit'])->first()->id;
            $product['currency_id'] = CurrencyHelper::getDefaultCurrency()->id;
        }
    }
}
