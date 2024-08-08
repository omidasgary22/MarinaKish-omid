<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFAQRequest extends FormRequest
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
            'question' => 'required|string|max:300',
            'answer' => 'required|string',
        ];
    }


    public function message()
    {
        return [
            'question.required' => 'فیلد سوال الزامی است.',
            'question.string' => 'فیلد سوال باید متن باشد.',
            'question.max' => 'فیلد سوال نباید بیشتر از ۲۵۵ کاراکتر باشد.',
            'answer.required' => 'فیلد پاسخ الزامی است.',
            'answer.string' => 'فیلد پاسخ باید متن باشد.',
        ];
    }
}
