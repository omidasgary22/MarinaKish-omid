<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PassengerRequest extends FormRequest
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
            'name_and_surname' => 'required|string|max:255',
            'gender' => 'required|in:مرد,زن',
            'age' => 'required|integer|min:0',
            'national_code' => 'required|ir_national_code',
        ];
    }


    public function messages()
    {
        return [
            'name_and_surname.required' => 'نام و نام خانوادگی ضروری است.',
            'gender.required' => 'جنسیت ضروری است.',
            'gender.in' => 'جنسیت باید یکی از مقادیر زن یا مرد باشد.',
            'age.required' => 'سن ضروری است.',
            'age.integer' => 'سن باید یک عدد صحیح باشد.',
            'age.min' => 'سن نمی‌تواند کمتر از صفر باشد.',
            'ir_national_code.required' => 'کد ملی ضروری است.',
            'ir_national_code.string' => 'کد ملی معتبر نمی باشد.',
            'ir_national_code.unique' => 'کد ملی قبلاً استفاده شده است.',
        ];
    }
}
