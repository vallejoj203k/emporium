@extends('layouts.app')

@section('title', 'Detalle del cliente')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-4">
            <div>
                <h1 class="h4 mb-1">{{ $customer->first_name }} {{ $customer->last_name }}</h1>
                <div class="text-muted">{{ $customer->documentType?->name }} {{ $customer->document_number }}</div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('customer-memberships.create') }}" class="btn btn-outline-success">Nueva membresía</a>
                <a href="{{ route('customers.edit', $customer) }}" class="btn btn-outline-primary">Editar</a>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-md-4">
                <div class="border rounded p-3"><small class="text-muted d-block">Teléfono</small>{{ $customer->phone ?? '-' }}</div>
            </div>
            <div class="col-md-4">
                <div class="border rounded p-3"><small class="text-muted d-block">Estado</small>{{ $customer->status }}</div>
            </div>
            <div class="col-md-4">
                <div class="border rounded p-3"><small class="text-muted d-block">Inscripción</small>{{ $customer->registered_at?->format('d/m/Y') }}</div>
            </div>
            <div class="col-12">
                <div class="border rounded p-3"><small class="text-muted d-block">Observaciones</small>{{ $customer->observations ?? 'Sin observaciones' }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mt-3">
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-body table-responsive">
                <h2 class="h5 mb-3">Membresías</h2>
                <table class="table table-sm align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Plan</th>
                            <th>Vence</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customer->memberships as $membership)
                        <tr>
                            <td>{{ $membership->plan->name }}</td>
                            <td>{{ $membership->end_date?->format('d/m/Y') }}</td>
                            <td>{{ $membership->status }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-muted">Sin membresías.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-body table-responsive">
                <h2 class="h5 mb-3">Pagos</h2>
                <table class="table table-sm align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Valor</th>
                            <th>Método</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customer->payments as $payment)
                        <tr>
                            <td>{{ $payment->payment_date?->format('d/m/Y') }}</td>
                            <td>${{ number_format($payment->amount, 0, ',', '.') }}</td>
                            <td>{{ $payment->method->name }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-muted">Sin pagos.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection