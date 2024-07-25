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
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'status' => 'required|in:open,closed,pending',
            'priority' => 'required|in:low,medium,high',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'عنوان تیکت الزامی است.',
            'body.required' => 'متن تیکت الزامی است.',
            'status.required' => 'وضعیت تیکت الزامی است.',
            'status.in' => 'وضعیت تیکت معتبر نیست.',
            'priority.required' => 'اولویت تیکت الزامی است.',
            'priority.in' => 'اولویت تیکت معتبر نیست.',
        ];
    }
}
