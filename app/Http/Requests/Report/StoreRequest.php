<?php

namespace App\Http\Requests\Report;

use App\Constants\ReportTypes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', Rule::in(ReportTypes::TYPES)],
        ];
    }
}
