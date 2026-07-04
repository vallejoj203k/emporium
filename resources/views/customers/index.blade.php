@extends('layouts.app')

@section('title', 'Clientes')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-2">
    <div>
        <h1 class="h3 mb-1">Clientes</h1>
        <p class="text-muted mb-0">Gestión de clientes del gimnasio</p>
    </div>
    <div class="d-flex gap-2 flex-column flex-md-row">
        <form method="GET" action="{{ route('customers.index') }}" class="d-flex gap-2">
            <input type="search" name="search" value="{{ $search ?? '' }}" class="form-control" placeholder="Buscar por nombre, documento o ID">
            <button class="btn btn-outline-primary">Buscar</button>
        </form>
        <a href="{{ route('customers.create') }}" class="btn btn-primary">Nuevo cliente</a>
    </div>
</div>

<div class="card">
    <div class="card-body table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Documento</th>
                    <th>Teléfono</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                <tr>
                    <td class="fw-semibold">#{{ $customer->id }} · {{ $customer->first_name }} {{ $customer->last_name }}</td>
                    <td>{{ $customer->documentType?->code }} {{ $customer->document_number }}</td>
                    <td>{{ $customer->phone ?? '-' }}</td>
                    <td><span class="badge bg-{{ $customer->status === 'active' ? 'success' : ($customer->status === 'suspended' ? 'warning' : 'secondary') }}">{{ $customer->status }}</span></td>
                    <td class="text-end">
                        <a href="{{ route('customers.show', $customer) }}" class="btn btn-sm btn-outline-secondary">Ver</a>
                        <a href="{{ route('customers.edit', $customer) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">No hay clientes registrados o no hubo coincidencias con la búsqueda.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $customers->links() }}</div>
@endsection