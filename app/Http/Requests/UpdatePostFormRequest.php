<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostFormRequest extends FormRequest
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
            'topic' => 'sometimes|string|max:50',       // Valida solo si el campo está presente.
            'content' => 'sometimes|string|max:255',    // Valida solo si el campo está presente.
        ];
    }

    public function messages(): array
    {
        return [
            'topic.string' => 'El topic debe ser un texto.',
            'topic.max' => 'El topic no debe tener más de 50 caracteres.',
            'content.string' => 'El contenido debe ser un texto.',
            'content.max' => 'El contenido no debe tener más de 255 caracteres.',
        ];
    }
}
