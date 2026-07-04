@extends('layouts.app')

@section('title', 'Planes de membresía')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h3 mb-1">Planes de membresía</h1>
        <p class="text-muted mb-0">Catálogo de planes del gimnasio</p>
    </div>
    <a href="{{ route('membership-plans.create') }}" class="btn btn-primary">Nuevo plan</a>
</div>

<div class="card">
    <div class="card-body table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Duración</th>
                    <th>Precio</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($plans as $plan)
                <tr>
                    <td>{{ $plan->name }}</td>
                    <td>{{ $plan->duration_days }} días</td>
                    <td>${{ number_format($plan->price, 0, ',', '.') }}</td>
                    <td><span class="badge bg-{{ $plan->is_active ? 'success' : 'secondary' }}">{{ $plan->is_active ? 'Activo' : 'Inactivo' }}</span></td>
                    <td class="text-end">
                        <a href="{{ route('membership-plans.edit', $plan) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                        <form action="{{ route('membership-plans.destroy', $plan) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Desactivar</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">No hay planes registrados.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $plans->links() }}</div>
@endsection