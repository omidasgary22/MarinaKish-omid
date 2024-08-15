<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DiscountCodeRequest extends FormRequest
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
            'title' => 'required|string',
            'code' => 'required|string|unique:discount_codes,code,' . $this->route('discount_code'),
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'quantity' => 'required|integer|min:1',
            'starts_at' => 'required|date',
            'expires_at' => 'required|date|after_or_equal:starts_at',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'فیلد عنوان کد تخفیف الزامی است.',
            'title.string' => 'فیلد عنوان کد تخفیف باید یک رشته باشد.',
            'code.required' => 'فیلد کد تخفیف الزامی است.',
            'code.string' => 'فیلد کد تخفیف باید یک رشته باشد.',
            'code.unique' => 'فیلد کد تخفیف باید منحصر به فرد باشد و مقدار وارد شده قبلاً استفاده شده است.',
            'discount_percentage.required' => 'فیلد درصد تخفیف الزامی است.',
            'discount_percentage.numeric' => 'فیلد درصد تخفیف باید یک عدد باشد.',
            'discount_percentage.min' => 'فیلد درصد تخفیف نباید کمتر از ۰ باشد.',
            'discount_percentage.max' => 'فیلد درصد تخفیف نباید بیشتر از ۱۰۰ باشد.',
            'quantity.required' => 'فیلد تعداد کد تخفیف الزامی است.',
            'quantity.integer' => 'فیلد تعداد کد تخفیف باید یک عدد صحیح باشد.',
            'quantity.min' => 'فیلد تعداد کد تخفیف نباید کمتر از ۱ باشد.',
            'starts_at.required' => 'فیلد تاریخ شروع اعتبار الزامی است.',
            'starts_at.date' => 'فیلد تاریخ شروع اعتبار باید یک تاریخ معتبر باشد.',
            'expires_at.required' => 'فیلد تاریخ انقضای اعتبار الزامی است.',
            'expires_at.date' => 'فیلد تاریخ انقضای اعتبار باید یک تاریخ معتبر باشد.',
            'expires_at.after_or_equal' => 'فیلد تاریخ انقضای اعتبار باید بعد از یا برابر با تاریخ شروع اعتبار باشد.',
        ];
    }
}
