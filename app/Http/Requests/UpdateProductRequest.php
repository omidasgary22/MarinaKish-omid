<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'name' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|integer',
            'time' => 'sometimes|required|integer',
            'age_limited' => 'sometimes|required|integer',
            'total' => 'sometimes|required|integer',
            'pending' => 'sometimes|required|integer',
            'description' => 'sometimes|required|string',
            'started_at' => 'sometimes|required|date_format:H:i',
            'ended_at' => 'sometimes|required|date_format:H:i',

        ];
    }
}
ّ