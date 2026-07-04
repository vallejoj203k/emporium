@extends('layouts.app')

@section('title', 'Detalle de membresía')

@section('content')
<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
                <h1 class="h4 mb-1">{{ $membership->customer->first_name }} {{ $membership->customer->last_name }}</h1>
                <div class="text-muted">{{ $membership->plan->name }} · {{ $membership->start_date?->format('d/m/Y') }} - {{ $membership->end_date?->format('d/m/Y') }}</div>
            </div>
            <span class="badge bg-{{ $membership->status === 'active' ? 'success' : ($membership->status === 'expired' ? 'secondary' : 'danger') }}">{{ $membership->status }}</span>
        </div>

        <div class="row g-3">
            <div class="col-md-4">
                <div class="border rounded p-3"><small class="text-muted d-block">Valor pagado</small>${{ number_format($membership->paid_amount, 0, ',', '.') }}</div>
            </div>
            <div class="col-md-4">
                <div class="border rounded p-3"><small class="text-muted d-block">Método de pago</small>{{ $membership->paymentMethod->name }}</div>
            </div>
            <div class="col-md-4">
                <div class="border rounded p-3"><small class="text-muted d-block">Registrado por</small>{{ $membership->registeredBy->name }}</div>
            </div>
            <div class="col-12">
                <div class="border rounded p-3"><small class="text-muted d-block">Observaciones</small>{{ $membership->observations ?? 'Sin observaciones' }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body table-responsive">
        <h2 class="h5 mb-3">Pagos asociados</h2>
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Método</th>
                    <th>Valor</th>
                    <th>Comprobante</th>
                </tr>
            </thead>
            <tbody>
                @forelse($membership->payments as $payment)
                <tr>
                    <td>{{ $payment->payment_date?->format('d/m/Y') }}</td>
                    <td>{{ $payment->method->name }}</td>
                    <td>${{ number_format($payment->amount, 0, ',', '.') }}</td>
                    <td>{{ $payment->receipt_number ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted py-4">No hay pagos asociados.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection