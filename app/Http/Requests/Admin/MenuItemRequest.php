<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class MenuItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name'             => ['required', 'string', 'max:100'],
            'description'      => ['nullable', 'string'],
            'price'            => ['required', 'numeric', 'min:0.01'],
            'preparation_time' => ['nullable', 'integer', 'min:1'],
            'category_id'      => ['required', 'integer', 'exists:categories,id'],
            'menu_image'       => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5000'],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'name'             => $this->input('menu_name'),
            'description'      => $this->input('menu_description'),
            'price'            => $this->input('menu_price'),
            'preparation_time' => $this->input('menu_prep_time'),
            'category_id'      => $this->input('menu_category'),
        ]);
    }
}
