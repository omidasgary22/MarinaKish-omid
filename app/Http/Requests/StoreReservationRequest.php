<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
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
            'sans_id' => 'required|exists:sans,id',
            'reservation_date' => 'required|date',
            'product_id' => 'required|exists:products,id',
            'passengers' => 'array',
            'passengers.*.id' => 'exists:passengers,id',
            'discount_code' => 'nullable|string',
        ];
    }


    public function messages()
    {
        return [
            'sans_id.required' => 'سانس مورد نظر الزامی است.',
            'sans_id.exists' => 'سانس انتخابی معتبر نیست.',
            'reservation_date.required' => 'تاریخ رزرو الزامی است.',
            'reservation_date.date' => 'تاریخ رزرو معتبر نیست.',
            'product_id.required' => 'تفریح مورد نظر الزامی است.',
            'product_id.exists' => 'تفریح انتخابی معتبر نیست.',
            'passengers.array' => 'اطلاعات مسافران باید به صورت آرایه باشد.',
            'passengers.*.id.exists' => 'شناسه مسافر معتبر نیست.',
            'discount_code.string' => 'کد تخفیف باید رشته‌ای باشد.',
        ];
    }
}
