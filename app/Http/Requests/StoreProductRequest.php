<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'price' => 'required|integer',
            'time' => 'required|integer',
            'age_limited' => 'required|integer',
            'total' => 'required|integer',
            'description' => 'required|string',
            'started_at' => 'required|date_format:H:i',
            'ended_at' => 'required|date_format:H:i',
        ];
    }
}
