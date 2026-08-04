<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'shipping_name' => 'required|string|max:255',
            'shipping_zip' => 'nullable|string|max:20',
            'shipping_address' => 'required|string|max:255',
            'shipping_tel' => 'nullable|string|max:20',
            'payment_method' => 'required|in:cod,credit_card',
        ];
    }
}
