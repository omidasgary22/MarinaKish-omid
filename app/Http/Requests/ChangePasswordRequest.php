<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Symfony\Contracts\Service\Attribute\Required;

class ChangePasswordRequest extends FormRequest
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
            'current_password' => 'Required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ];
    }



    public function messages()
    {
        return [
            'current_password.required' => 'رمز عبور فعلی ضروری است.',
            'new_password.required' => 'رمز عبور جدید ضروری است.',
            'new_password.min' => 'رمز عبور جدید باید حداقل 8 کاراکتر باشد.',
            'new_password.confirmed' => 'رمز عبور جدید و تأیید آن باید مطابقت داشته باشند.',
        ];
    }
}
