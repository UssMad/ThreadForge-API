<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBlueprintRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'audience_target' => 'required|string',
            'tone' => 'required|string',
            'max_hashtags' => 'required|integer|min:0',
            'max_characters' => 'required|integer|min:1',
            'additional_rules' => 'nullable',
        ];
    }
}
