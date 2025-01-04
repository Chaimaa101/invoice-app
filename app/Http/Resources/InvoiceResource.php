<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'number' => $this->number,
            'customer' => $this->customer ? [
                'id' => $this->customer->id,
                'firstname' => $this->customer->firstname,
                'lastname' => $this->customer->lastname,
                'email' => $this->customer->email,
                'address' => $this->customer->address,
            ] : null,
            'date' => $this->date,
            'due_date' => $this->due_date,
            'sub_total' => $this->sub_total,
            'discount' => $this->discount,
            'total' => $this->total,
            'terms_and_conditions' => $this->terms_and_conditions,
            'invoiceItem' => $this->items && $this->items->isNotEmpty() ? $this->items->map(function ($item) {
                return [
                    'product_id' => $item->product_id,
                    'product_name' => $item->product ? $item->product->name : null,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                ];
            }) : [],
        ];
    }
}
