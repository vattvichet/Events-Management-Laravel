<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;

class Update_EventRequest extends FormRequest
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
            'name' => 'sometimes|max:100',
            'description' => 'sometimes|max:255',
            'start_date' => 'sometimes|date|before:end_date',
            'end_date' => 'sometimes|date|after:start_date',
        ];
    }
}
