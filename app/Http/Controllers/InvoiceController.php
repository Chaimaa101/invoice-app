<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Http\Resources\InvoiceResource;
use App\Models\Counter;
use App\Models\InvoiceItem;
use Illuminate\Support\Facades\Log; 
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() : AnonymousResourceCollection
    {
        $invoices = Invoice::with('customer')->orderBy('id', 'DESC')->get();
        return InvoiceResource::collection($invoices);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $counter = Counter::where('key', 'invoice')->first();
        $random =  Counter::where('key', 'invoice')->first();

        $invoice = Invoice::orderBy('id', 'DESC')->first();
        if ($invoice) {
            $invoice = $invoice->id + 1;
            $counters = $counter->value + $invoice;
        } else {
            $counters = $counter->value;
        }

        $infos = [
            'number' => $counter->prefix . $counters,
            'customer_id' => null,
            'customer' => null,
            'date' => date('Y-m-d'),
            'due_date' => null,
            'reference' => null,
            'discount' => 0,
            'term and conditions' => 'Default Terms and  Conditions',
            'items' => [
                [
                    'product_id' => null,
                    'product' => null,
                    'price' => 0,
                    'quantity' => 1,

                ]
            ]
        ];
        return response()->json($infos);
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(StoreInvoiceRequest $request)
    {
        try {
        log::info('Invoice Data:', $request->all());
            $invoiceItem = $request->input("invoice_item");

            $invoiceData = [
                'sub_total' => $request->input("sub_total"),
                'total' => $request->input("total"),
                'reference' => $request->input("reference"),
                'number' => $request->input("number"),
                'terms_and_conditions' => $request->input("terms_and_conditions"),
                'date' => $request->input("date"),
                'due_date' => $request->input("due_date"),
                'customer_id' => $request->input("customer_id"),
                'discount' => $request->input("discount"),
            ];

            $invoice = Invoice::create($invoiceData);

            foreach (json_decode($invoiceItem) as $item) {
                InvoiceItem::create([
                    'product_id' => $item->id,
                    'invoice_id' => $invoice->id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                ]);
            }

    return response()->json(['message' => 'Invoice created successfully'], 201); 
    } catch (\Exception $e) {
        Log::error('Error creating invoice:', ['exception' => $e]);

        if ($e instanceof \Illuminate\Validation\ValidationException) {
            return response()->json(['errors' => $e->errors()], 422); 
        }

        return response()->json(['message' => 'An error occurred while creating the invoice.'], 500);
    
    }
}
    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        //
    }
}
