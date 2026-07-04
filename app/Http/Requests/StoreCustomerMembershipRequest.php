<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerMembershipRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'exists:customers,id'],
            'membership_plan_id' => ['required', 'exists:membership_plans,id'],
            'payment_method_id' => ['required', 'exists:payment_methods,id'],
            'start_date' => ['required', 'date'],
            'paid_amount' => ['required', 'numeric', 'min:0'],
            'receipt_number' => ['nullable', 'string', 'max:80', 'unique:payments,receipt_number'],
            'observations' => ['nullable', 'string'],
        ];
    }
}
