<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
{
    protected function failedValidation(Validator $validator) //validation errory gormek un hokman gerek
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $validator->errors(),
        ], 422));
    }


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
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'nullable|string|min:6',
            'role_id' => 'required|exists:Roles,id'
        ];
        if ($this->isMethod('post')) {
            $rules['password'] = 'required|string|min:6';
        }
        return $rules;
    }

    public function messages()
    {
        $messages = [
            'email.required' => 'Вы должны обязательно ввести свою электронную почту!',
            'password.required' => 'Вы должны обязательно ввести свой пароль!',
        ];

        if ($this->isMethod('post')) {
            $messages['password.required'] = 'Вы должны обязательно ввести свой пароль!';
        }

        return $messages;
    }
}
