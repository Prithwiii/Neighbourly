<?php

namespace App\Http\Requests\Payments;

use Illuminate\Foundation\Http\FormRequest;

class StoreDonationPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'donor_name' => ['required', 'string', 'max:120'],
            'donor_email' => ['nullable', 'email', 'max:255'],
            'donor_phone' => ['required', 'string', 'max:30'],
            'amount' => ['required', 'numeric', 'min:1'],
            'purpose' => ['nullable', 'string', 'max:255'],
            'message' => ['nullable', 'string', 'max:1000'],
        ];
    }
}