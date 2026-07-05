<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'document_type_id' => ['required', 'exists:document_types,id'],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'document_number' => ['required', 'string', 'max:40', 'unique:customers,document_number'],
            'birth_date' => ['nullable', 'date'],
            'gender' => ['nullable', 'in:male,female,other'],
            'phone' => ['nullable', 'string', 'max:30'],
            'emergency_contact_name' => ['nullable', 'string', 'max:120'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:30'],
            'registered_at' => ['required', 'date'],
            'status' => ['required', 'in:active,suspended,expired'],
            'observations' => ['nullable', 'string'],

            // Membresía inicial opcional: si se elige un plan, los demás campos se vuelven obligatorios.
            'membership_plan_id' => ['nullable', 'exists:membership_plans,id'],
            'payment_method_id' => ['nullable', 'required_with:membership_plan_id', 'exists:payment_methods,id'],
            'start_date' => ['nullable', 'required_with:membership_plan_id', 'date'],
            'paid_amount' => ['nullable', 'required_with:membership_plan_id', 'numeric', 'min:0'],
            'receipt_number' => ['nullable', 'string', 'max:80', 'unique:payments,receipt_number'],
            'membership_observations' => ['nullable', 'string'],
        ];
    }
}
