<?php

namespace App\Http\Requests;

use App\Models\Product;
use App\Traits\FilterableRequest;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    use FilterableRequest;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [...$this->filterableRules(), []];
    }

    public function filterableFields(): array
    {
        return [
            Product::NAME,
            Product::DESCRIPTION,
            Product::PRICE,
            Product::CATEGORY_ID,
            Product::USER_ID
        ];
    }
}
