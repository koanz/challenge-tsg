<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;

class UpdateUserFormRequest extends BaseFormRequest
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
            'name' => 'sometimes|string|max:50',
            'email' => 'sometimes|string|filled|max:50|unique:users',
            'password' => 'sometimes|min:6|max:20|confirmed',
        ];
    }

    /**
     * Mensajes para errores de validación.
     */
    public function messages(): array
    {
        return [
            'name.string' => 'El campo :attribute no puede estar vacío.',
            'name.max' => 'El campo :attribute no puede superar los 50 caracteres.',
            'email.string' => 'El campo :attribute no puede estar vacío.',
            'email.max' => 'El campo :attribute no puede superar los 50 caracteres.',
            'email.unique' => 'El :attribute ya está registrado.',
            'password.min' => 'El campo :attribute debe tener al menos 6 caracteres.',
            'password.max' => 'El campo :attribute no puede superar los 20 caracteres.',
            'password.confirmed' => 'La confirmación del :attribute no coincide.',
        ];
    }

}
