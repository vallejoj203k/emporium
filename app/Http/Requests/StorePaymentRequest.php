<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'exists:customers,id'],
            'customer_membership_id' => ['nullable', 'exists:customer_memberships,id'],
            'payment_method_id' => ['required', 'exists:payment_methods,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'payment_date' => ['required', 'date'],
            'receipt_number' => ['nullable', 'string', 'max:80', 'unique:payments,receipt_number'],
            'observations' => ['nullable', 'string'],
        ];
    }
}
