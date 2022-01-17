<?php

namespace App\Jobs;

use App\Helpers\CurrencyHelper;
use App\Models\Product;
use App\Models\WeightUnit;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;

class ImportProducts implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

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
        foreach ($csv as $record) {
            $product = $record;
            $product['weight_unit_id'] = WeightUnit::where('weight_unit_alias', $record['weight_unit'])->first()->id;
            $product['currency_id'] = CurrencyHelper::getDefaultCurrency()->id;
            Product::create($product);
        }
    }
}
