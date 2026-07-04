<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $customerId = $this->route('customer')?->id;

        return [
            'document_type_id' => ['required', 'exists:document_types,id'],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'document_number' => [
                'required',
                'string',
                'max:40',
                Rule::unique('customers', 'document_number')
                    ->ignore($customerId)
                    ->where(fn($query) => $query->where('document_type_id', $this->input('document_type_id'))),
            ],
            'birth_date' => ['nullable', 'date'],
            'gender' => ['nullable', 'in:male,female,other'],
            'phone' => ['nullable', 'string', 'max:30'],
            'emergency_contact_name' => ['nullable', 'string', 'max:120'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:30'],
            'registered_at' => ['required', 'date'],
            'status' => ['required', 'in:active,suspended,expired'],
            'observations' => ['nullable', 'string'],
        ];
    }
}
