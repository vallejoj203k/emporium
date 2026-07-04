<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Models\Customer;
use App\Models\CustomerMembership;
use App\Models\Payment;
use App\Models\PaymentMethod;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('customer.documentType', 'membership.plan', 'method', 'user')
            ->latest('payment_date')
            ->paginate(10);

        return view('payments.index', compact('payments'));
    }

    public function create()
    {
        $customers = Customer::orderBy('first_name')->get();
        $memberships = CustomerMembership::with('customer', 'plan')->latest()->get();
        $paymentMethods = PaymentMethod::where('is_active', true)->orderBy('name')->get();

        return view('payments.create', compact('customers', 'memberships', 'paymentMethods'));
    }

    public function store(StorePaymentRequest $request)
    {
        $validated = $request->validated();
        $validated['receipt_number'] = $validated['receipt_number'] ?? $this->generateReceiptNumber();

        $payment = Payment::create($validated + [
            'registered_by' => $request->user()->id,
        ]);

        return redirect()->route('payments.receipt', $payment)->with('success', 'Pago registrado correctamente.');
    }

    public function receipt(Payment $payment)
    {
        $payment->load('customer.documentType', 'membership.plan', 'method', 'user');

        return view('payments.receipt', compact('payment'));
    }

    public function receiptPdf(Payment $payment)
    {
        $payment->load('customer.documentType', 'membership.plan', 'method', 'user');

        $pdf = Pdf::loadView('payments.receipt-pdf', compact('payment'))->setPaper('a4', 'portrait');

        return $pdf->download('comprobante_' . $payment->receipt_number . '.pdf');
    }

    private function generateReceiptNumber(): string
    {
        do {
            $receiptNumber = 'REC-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
        } while (Payment::where('receipt_number', $receiptNumber)->exists());

        return $receiptNumber;
    }
}
