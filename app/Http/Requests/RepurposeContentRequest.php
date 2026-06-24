<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RepurposeContentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'blueprint_id' => 'required|integer|exists:blueprints,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ];
    }
}
