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
            'discount_percentage' => 'integer|min:0|max:100',
            'off_suggestion' => 'required|in:yes,no',
            'tip' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'نام محصول الزامی است.',
            'name.string' => 'نام محصول باید یک رشته باشد.',
            'name.max' => 'نام محصول نباید بیشتر از ۲۵۵ کاراکتر باشد.',
            'price.required' => 'قیمت محصول الزامی است.',
            'price.integer' => 'قیمت محصول باید یک عدد صحیح باشد.',
            'time.required' => 'زمان محصول الزامی است.',
            'time.integer' => 'زمان محصول باید یک عدد صحیح باشد.',
            'age_limited.required' => 'محدودیت سنی محصول الزامی است.',
            'age_limited.integer' => 'محدودیت سنی باید یک عدد صحیح باشد.',
            'total.required' => 'تعداد کل محصول الزامی است.',
            'total.integer' => 'تعداد کل باید یک عدد صحیح باشد.',
            'pending.required' => 'تعداد معلق محصول الزامی است.',
            'pending.integer' => 'تعداد معلق باید یک عدد صحیح باشد.',
            'description.required' => 'توضیحات محصول الزامی است.',
            'description.string' => 'توضیحات باید یک رشته باشد.',
            'started_at.required' => 'زمان شروع الزامی است.',
            'started_at.date_format' => 'زمان شروع باید در فرمت ساعت:دقیقه باشد.',
            'ended_at.required' => 'زمان پایان الزامی است.',
            'ended_at.date_format' => 'زمان پایان باید در فرمت ساعت:دقیقه باشد.',
            'discount_percentage.integer' => 'درصد تخفیف باید یک عدد صحیح باشد.',
            'discount_percentage.min' => 'درصد تخفیف نمی‌تواند کمتر از ۰ باشد.',
            'discount_percentage.max' => 'درصد تخفیف نمی‌تواند بیشتر از ۱۰۰ باشد.',
            'off_suggestion' => 'پیشنهاد تخفیف',
            'tip' => 'نوشتن نکات لازم برای این تفریح الزامی می باشد',
        ];
    }
}
