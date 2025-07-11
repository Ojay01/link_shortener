<?php
// app/Http/Requests/ShortenUrlRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShortenUrl extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'url' => 'required|url|max:2048',
            'custom_code' => 'sometimes|string|min:3|max:50|alpha_dash|unique:urls,custom_code',
            'expires_at' => 'sometimes|date|after:now',
        ];
    }

    public function messages(): array
    {
        return [
            'url.required' => 'The URL field is required.',
            'url.url' => 'The URL must be a valid URL.',
            'url.max' => 'The URL may not be greater than 2048 characters.',
            'custom_code.unique' => 'This custom code is already taken.',
            'custom_code.alpha_dash' => 'The custom code may only contain letters, numbers, dashes and underscores.',
            'expires_at.after' => 'The expiration date must be in the future.',
        ];
    }
}