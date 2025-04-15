<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;

class UserRegisterRequest extends BaseFormRequest
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
            'name' => 'required|string|max:50',
            'email' => 'required|string|email|max:50|unique:users',
            'password' => 'required|string|min:6|max:20|confirmed',
        ];
    }

    /**
     * Mensajes para errores de validación.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El campo :attribute es obligatorio.',
            'name.max' => 'El campo :attribute no puede superar los 50 caracteres.',
            'email.required' => 'El campo :attribute es obligatorio.',
            'email.email' => 'El formato del :attribute es inválido.',
            'email.max' => 'El campo :attribute no puede superar los 50 caracteres.',
            'email.unique' => 'El :attribute ya se encuentra registrado.',
            'password.required' => 'El campo :attribute es obligatorio.',
            'password.min' => 'El campo :attribute debe tener al menos 6 caracteres.',
            'password.max' => 'El campo :attribute no puede superar los 20 caracteres.',
            'password.confirmed' => 'La confirmación del :attribute no coincide.',
        ];
    }

}
