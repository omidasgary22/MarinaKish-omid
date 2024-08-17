<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTicketRequest extends FormRequest
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
            'body' => 'sometimes|required|string',
            'priority' => 'sometimes|required|in:low,medium,high',
            'status' => 'sometimes|required|in:open,closed,in_progress',
        ];
    }


    public function messages()
    {
        return [
            'title.required' => 'عنوان الزامی است',
            'body.required' => 'متن الزامی است',
            'priority.required' => 'اولویت الزامی است',
            'status.required' => 'وضعیت الزامی است',
        ];
    }
}
