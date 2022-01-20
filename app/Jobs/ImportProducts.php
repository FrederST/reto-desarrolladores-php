<?php

namespace App\Jobs;

use App\Constants\ImportStatus;
use App\Helpers\CurrencyHelper;
use App\Models\User;
use App\Models\WeightUnit;
use App\Notifications\ImportStatusChange;
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

    private string $filePath;
    private string $userId;

    public function __construct(string $filePath, string $userId)
    {
        $this->filePath = $filePath;
        $this->userId = $userId;
    }

    public function handle(): void
    {
        $user = User::find($this->userId);

        $csv = Reader::createFromPath(Storage::path($this->filePath))
        ->setHeaderOffset(0)
        ->setDelimiter(';');

        $validator = Validator::make(['products' => iterator_to_array($csv->getRecords())], self::RULES);

        if ($validator->fails()) {
            $user->notify(new ImportStatusChange(ImportStatus::STATUS_FAIL, $validator->errors()->__toString()));
        } else {
            foreach ($csv as $value) {
                $product = $value;
                $product['weight_unit_id'] = WeightUnit::where('weight_unit_alias', $product['weight_unit'])->first()->id;
                $product['currency_id'] = CurrencyHelper::getDefaultCurrency()->id;
            }
            $user->notify(new ImportStatusChange(ImportStatus::STATUS_FAIL, 'All Good'));
        }
    }
}
