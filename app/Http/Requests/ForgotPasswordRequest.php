<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordRequest extends FormRequest
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
            'national_code' => 'required|ir_national_code',
            'phone_number' => 'required|ir_mobile:zero',
        ];
    }

    public function messages()
    {
        return [
            'national_code.required' => 'کد ملی الزامی است.',
            'national_code.ir_national_code' => 'کد ملی معتبر نمی باشد',
            'phone_number.required' => 'شماره موبایل الزامی است.',
            'phone_number.ir_mobile:zero' => 'شماره موبایل صحیح نمی باشد',
            'phone_number.exists' => 'کاربری با این شماره موبایل یافت نشد.',
        ];
    }
}
