<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserFormRequest extends FormRequest
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
            'name' => 'sometimes|string|max:50',                // sometimes => valida solo si el campo está presente.
            'email' => 'sometimes|string|max:50|unique:users',
            'password' => 'sometimes|string|min:6|max:20|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'name.max' => 'El campo :attribute no puede exceder los 50 caracteres.',
            'email.max' => 'El campo :attribute no puede exceder los 50 caracteres.',
            'email.unique' => 'El :attribute ya está registrado.',
            'password.min' => 'El campo :attribute debe tener al menos 6 caracteres.',
            'password.max' => 'El campo :attribute no debe superar los 20 caracteres.',
            'password.confirmed' => 'La confirmación del :attribute no coincide.',
        ];
    }
}
