<?php

namespace App\Actions\Product;

use App\Exceptions\ImportProductException;
use App\Jobs\ImportProducts;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use League\Csv\Reader;

class ImportAction
{
    public function execute(string $filePath): void
    {
        $csv = Reader::createFromPath(Storage::path($filePath))
        ->setHeaderOffset(0)
        ->setDelimiter(';');

        $validator = Validator::make(['products' => iterator_to_array($csv->getRecords())], ImportProducts::RULES);

        throw_if(
            $validator->fails(),
            ImportProductException::class,
            $validator->errors()->__toString()
        );
    }
}
