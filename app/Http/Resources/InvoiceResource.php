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
            'customer' => $this->customer,
            'number' => $this->number,
            'date' => $this->date,
            'due_date' => $this->due_date,
            'reference' => $this->reference,
            'sub_total' => $this->sub_total,
            'discount' => $this->discount,
            'total' => $this->total,
            'terms_and_conditions' => $this->terms_and_conditions,
            'invoiceItem' => InvoiceItemResource::collection($this->invoice_items),
        ];
    }
}
