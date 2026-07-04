@extends('layouts.app')

@section('title', 'Pagos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h3 mb-1">Pagos</h1>
        <p class="text-muted mb-0">Historial de ingresos del gimnasio</p>
    </div>
    <a href="{{ route('payments.create') }}" class="btn btn-primary">Registrar pago</a>
</div>

<div class="card">
    <div class="card-body table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Fecha</th>
                    <th>Método</th>
                    <th>Valor</th>
                    <th>Comprobante</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                <tr>
                    <td>{{ $payment->customer->first_name }} {{ $payment->customer->last_name }}</td>
                    <td>{{ $payment->payment_date?->format('d/m/Y') }}</td>
                    <td>{{ $payment->method->name }}</td>
                    <td>${{ number_format($payment->amount, 0, ',', '.') }}</td>
                    <td>{{ $payment->receipt_number ?? '-' }}</td>
                    <td class="text-end">
                        <a href="{{ route('payments.receipt', $payment) }}" class="btn btn-sm btn-outline-secondary">Factura</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">No hay pagos registrados.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $payments->links() }}</div>
@endsection