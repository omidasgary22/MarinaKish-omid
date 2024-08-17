<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReservationRequest extends FormRequest
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
            'sans_id' => 'sometimes|exists:sans,id',
            'reservation_code' => 'sometimes|date',
            'product_id' => 'sometimes|exists:products,id',
            'discount_code_id' => 'nullable|exists:discount_codes_,id',
        ];
    }

    public function messages()
    {
        return [
            'sans_id.exists' => 'سانس انتخاب شده وجود ندارد.',
            'reservation_date.date' => 'تاریخ رزرو معتبر نیست.',
            'product_id.exists' => 'محصول انتخاب شده وجود ندارد.',
            'discount_code_id.exists' => 'کد تخفیف انتخاب شده وجود ندارد.',
        ];
    }
}
