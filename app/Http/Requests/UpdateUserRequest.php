<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
        $userId = $this->route('User');  //دریافت id از مسیر کاربر
        return [
            'name' => 'required|string|max:200',
            'phone_number' => 'required|string|regex:/^\+?[0-9]{10,15}$/',
            'date_of_birth' => 'required|date|before:today',
            'national_code' => 'required|string|size:10|regex:/^[0-9]{10}$/',
            'email' => 'required|string|email|max:255|unique:users,email' . $userId,
            'password' => 'nullable|string|min:8|confirmed',
        ];
    }
}
