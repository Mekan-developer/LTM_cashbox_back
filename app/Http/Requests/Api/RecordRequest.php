<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log as FacadesLog;
use Illuminate\Http\Exceptions\HttpResponseException;

class RecordRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
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

        return [
            'article_type' => 'nullable|string',
            'cashbox_id' => 'required|exists:cashboxes,id',
            'type' => 'required|boolean',
            'original_amount' => 'required|numeric|min:0',
            'original_currency' => 'required|string',
            'date' => 'required|date',
            'article_description' => 'nullable|string',
            'is_debt' => 'boolean',
            'link' => 'nullable|string',
            'object' => 'nullable|string',
        ];
    }
}
