<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Symfony\Contracts\Service\Attribute\Required;

class CreateUserRequest extends FormRequest
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
            'name' => 'required|string|max:200',
            'phone_number' => 'required|string|regex:/^\+?[0-9]{10,15}$/',
            'date_of_birth' => 'required|date|before:today',
            'national_code' => 'required|string|size:10|regex:/^[0-9]{10}$/',
            'email' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ];
    }
}
