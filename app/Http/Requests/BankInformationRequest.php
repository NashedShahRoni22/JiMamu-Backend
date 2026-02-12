<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BankInformationRequest extends FormRequest
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
            'name'               => 'required|string|max:255',
            'account_number'     => 'required|string|max:20',
            'institution_number' => 'nullable|string|size:3', // Usually 3 digits
            'transit_number'     => 'nullable|string|size:5', // Usually 5 digits
            'type'               => 'integer|in:0,1',
            'bank_document'      => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048', // 2MB Max
        ];
    }
}
