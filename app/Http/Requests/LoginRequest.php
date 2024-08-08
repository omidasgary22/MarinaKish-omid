<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'national_code' => 'required|string|max:10',
            'password' => 'required|string|min:8',
        ];
    }

    public function messages()
    {
        return [
            'national_code.required' => 'کد ملی الزامی است.',
            'national_code.max' => 'کد ملی نباید بیشتر از ۱۰ کاراکتر باشد.',
            'password.required' => 'رمز عبور الزامی است.',
            'password.min' => 'رمز عبور باید حداقل ۸ کاراکتر باشد.',
        ];
    }
}
