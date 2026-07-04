@extends('layouts.app')

@section('title', 'Membresías')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h3 mb-1">Membresías</h1>
        <p class="text-muted mb-0">Historial de compras y renovaciones</p>
    </div>
    <a href="{{ route('customer-memberships.create') }}" class="btn btn-primary">Registrar membresía</a>
</div>

<div class="card">
    <div class="card-body table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Plan</th>
                    <th>Inicio</th>
                    <th>Vence</th>
                    <th>Pagado</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($memberships as $membership)
                <tr>
                    <td>{{ $membership->customer->first_name }} {{ $membership->customer->last_name }}</td>
                    <td>{{ $membership->plan->name }}</td>
                    <td>{{ $membership->start_date?->format('d/m/Y') }}</td>
                    <td>{{ $membership->end_date?->format('d/m/Y') }}</td>
                    <td>${{ number_format($membership->paid_amount, 0, ',', '.') }}</td>
                    <td><span class="badge bg-{{ $membership->status === 'active' ? 'success' : ($membership->status === 'expired' ? 'secondary' : 'danger') }}">{{ $membership->status }}</span></td>
                    <td class="text-end">
                        <a href="{{ route('customer-memberships.show', $membership) }}" class="btn btn-sm btn-outline-secondary">Ver</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">No hay membresías registradas.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $memberships->links() }}</div>
@endsection