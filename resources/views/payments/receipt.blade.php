@extends('layouts.app')

@section('title', 'Factura / comprobante')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body p-4 p-lg-5">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h1 class="h4 mb-1">Comprobante de pago</h1>
                        <div class="text-muted">{{ $payment->receipt_number }}</div>
                    </div>
                    <div class="text-end">
                        <a href="{{ route('payments.receipt.pdf', $payment) }}" class="btn btn-danger btn-sm">Descargar PDF</a>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="border rounded p-3"><small class="text-muted d-block">Cliente</small><strong>{{ $payment->customer->first_name }} {{ $payment->customer->last_name }}</strong></div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded p-3"><small class="text-muted d-block">Documento</small><strong>{{ $payment->customer->documentType?->code }} {{ $payment->customer->document_number }}</strong></div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded p-3"><small class="text-muted d-block">Fecha</small><strong>{{ $payment->payment_date?->format('d/m/Y') }}</strong></div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded p-3"><small class="text-muted d-block">Método</small><strong>{{ $payment->method->name }}</strong></div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded p-3"><small class="text-muted d-block">Valor</small><strong>${{ number_format($payment->amount, 0, ',', '.') }}</strong></div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded p-3"><small class="text-muted d-block">Registrado por</small><strong>{{ $payment->user->name }}</strong></div>
                    </div>
                </div>

                <div class="border rounded p-3 mb-4">
                    <small class="text-muted d-block">Membresía asociada</small>
                    <div>
                        @if($payment->membership)
                        {{ $payment->membership->plan->name }} · {{ $payment->membership->start_date?->format('d/m/Y') }} al {{ $payment->membership->end_date?->format('d/m/Y') }}
                        @else
                        Pago no asociado a una membresía específica.
                        @endif
                    </div>
                </div>

                <div class="border rounded p-3 mb-4">
                    <small class="text-muted d-block">Observaciones</small>
                    <div>{{ $payment->observations ?? 'Sin observaciones' }}</div>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary">Volver</a>
                    <span class="text-muted small">Gym Management System</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection