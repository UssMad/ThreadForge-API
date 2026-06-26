<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBlueprintRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'audience_target' => 'sometimes|required|string',
            'tone' => 'sometimes|required|string',
            'max_hashtags' => 'sometimes|required|integer|min:0',
            'max_characters' => 'sometimes|required|integer|min:1',
            'additional_rules' => 'sometimes|nullable',
        ];
    }
}
