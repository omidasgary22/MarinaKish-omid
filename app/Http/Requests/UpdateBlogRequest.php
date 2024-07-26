<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBlogRequest extends FormRequest
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
            'title' => 'sometimes|required|string|max:255',
            'summary' => 'somtimes|required|string|max:500',
            'content' => 'sometimes|required|string',
            'duration_of_study' => 'sometimes|required|integer|min:1',
        ];
    }


    public function messages()
    {
        return [
            'title.required' => 'موضوع بلاگ الزامی است.',
            'title.string' => 'موضوع بلاگ باید رشته باشد.',
            'title.max' => 'موضوع بلاگ نباید بیشتر از 255 کاراکتر باشد.',
            'summary.required' => 'خلاصه بلاگ الزامی است.',
            'summary.string' => 'خلاصه بلاگ باید رشته باشد.',
            'summary.max' => 'خلاصه بلاگ نباید بیشتر از 500 کاراکتر باشد.',
            'content.required' => 'محتوای بلاگ الزامی است.',
            'content.string' => 'محتوای بلاگ باید رشته باشد.',
            'duration_of_study.required' => 'مدت زمان مطالعه بلاگ الزامی است.',
            'duration_of_study.integer' => 'مدت زمان مطالعه بلاگ باید عدد صحیح باشد.',
            'duration_of_study.min' => 'مدت زمان مطالعه بلاگ باید حداقل 1 دقیقه باشد.'
        ];
    }
}
