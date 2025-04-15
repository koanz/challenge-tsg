<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;

class LoginFormRequest extends BaseFormRequest
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
            'email' => 'required|string|email|max:50',
            'password' => 'required|string|min:6|max:20',
        ];
    }

    /**
     * Mensajes para errores de validación.
     */
    public function messages(): array
    {
        return [
            'email.required' => 'El campo :attribute es obligatorio.',
            'email.string' => 'El campo :attribute no puede estar vacío.',
            'email.email' => 'El formato del campo :attribute es inválido.',
            'email.max' => 'El campo :attribute no puede superar los 50 caracteres.',
            'password.required' => 'El campo :attribute es obligatoria.',
            'password.string' => 'El campo :attribute no puede estar vacío.',
            'password.min' => 'El campo :attribute debe tener al menos 6 caracteres.',
            'password.max' => 'El campo :attribute no puede superar los 20 caracteres.',
        ];
    }
}
