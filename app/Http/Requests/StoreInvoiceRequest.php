<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
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
            'sub_total' => 'required|numeric',
            'total' => 'required|numeric',
            'number' => 'required|string',
            'terms_and_conditions' => 'required|string',
            'date' => 'required|date',
            'due_date' => 'required|date',
            'reference' => 'required',
            'customer_id' => 'required|exists:customers,id', // Ensure that customer_id exists in the customers table
            'discount' => 'nullable|numeric',
            'invoiceItem' => 'required|array|min:1', // Make sure invoice_item is an array and not empty
            'invoiceItem.*.id' => 'required|exists:products,id', // Ensure each item has a valid product_id
            'invoiceItem.*.quantity' => 'required|numeric|min:1', // Quantity should be a number greater than 0
            'invoiceItem.*.unit_price' => 'required|numeric|min:0', // Unit price should be numeric and greater than or equal to 0
        ];
    }
}
