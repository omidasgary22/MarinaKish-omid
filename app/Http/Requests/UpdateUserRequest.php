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
            'national_code' => 'required|ir_national_code',
            'email' => 'string|email|nullable|max:255' . $userId,
            'password' => 'nullable|string|min:8',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'نام الزامی است.',
            'name.string' => 'نام باید یک رشته متنی باشد.',
            'name.max' => 'نام نباید بیشتر از ۲۵۵ کاراکتر باشد.',

            'phone_number.required' => 'شماره تلفن الزامی است.',
            'phone_number.string' => 'شماره تلفن باید یک رشته متنی باشد.',
            'phone_number.regex' => 'فرمت شماره تلفن معتبر نیست.',

            'date_of_birth.required' => 'تاریخ تولد الزامی است.',
            'date_of_birth.date' => 'تاریخ تولد باید یک تاریخ معتبر باشد.',
            'date_of_birth.before' => 'تاریخ تولد باید قبل از امروز باشد.',

            'national_code.required' => 'کد ملی الزامی است.',
            'national_code.ir_national_code' => 'کد ملی معتبر نمی باشد.',
           

            'email.required' => 'ایمیل الزامی است.',
            'email.string' => 'ایمیل باید یک رشته متنی باشد.',
            'email.email' => 'ایمیل وارد شده معتبر نیست.',
            'email.max' => 'ایمیل نباید بیشتر از ۲۵۵ کاراکتر باشد.',
            'email.unique' => 'ایمیل وارد شده قبلاً ثبت شده است.',

            'password.nullable' => 'رمز عبور می‌تواند خالی باشد.',
            'password.string' => 'رمز عبور باید یک رشته متنی باشد.',
            'password.min' => 'رمز عبور باید حداقل ۸ کاراکتر باشد.',
            'password.confirmed' => 'تأیید رمز عبور مطابقت ندارد.',
        ];
    }
}
