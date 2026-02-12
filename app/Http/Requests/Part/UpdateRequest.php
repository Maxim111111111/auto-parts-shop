<?php

namespace App\Http\Requests\Part;

use App\Models\Part;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        /** @var Part|null $part */
        $part = $this->route('part');

        return [
            'name' => ['required', 'string', 'max:150'],
            'sku' => ['required', 'string', 'max:64', Rule::unique('parts', 'sku')->ignore($part?->id)],
            'brand' => ['nullable', 'string', 'max:100'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:2000'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
