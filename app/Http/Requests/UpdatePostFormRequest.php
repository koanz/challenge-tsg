<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;

class UpdatePostFormRequest extends BaseFormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'topic' => 'sometimes|string|max:50',
            'content' => 'sometimes|string|max:255',
        ];
    }

    /**
     * Mensajes para errores de validación.
     */
    public function messages(): array
    {
        return [
            'topic.string' => 'El campo :attribute no puede estar vacío.',
            'topic.max' => 'El campo :attribute no puede superar los 50 caracteres.',
            'content.string' => 'El campo :attribute no puede estar vacío.',
            'content.max' => 'El campo :attribute no puede superar los 255 caracteres.',
        ];
    }

}
