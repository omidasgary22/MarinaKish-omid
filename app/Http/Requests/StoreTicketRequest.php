<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
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
            'title' => 'required|string|max:260',
            'body' => 'required|string',
            'priority' => 'required|in:low,medium,high',
        ];
    }


    public function messages()
    {
        return [
            'title.required' => 'عنوان الزامی است',
            'body.required' => 'متن الزامی است',
            'priority.required' => 'اولویت الزامی است',
        ];
    }
}
