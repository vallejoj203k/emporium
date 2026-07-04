@extends('layouts.app')

@section('title', 'Editar plan')

@section('content')
<div class="card">
    <div class="card-body">
        <h1 class="h4 mb-4">Editar plan de membresía</h1>
        <form method="POST" action="{{ route('membership-plans.update', $plan) }}" class="row g-3">
            @csrf
            @method('PUT')
            <div class="col-md-6"><label class="form-label">Nombre</label><input name="name" class="form-control" value="{{ old('name', $plan->name) }}" required></div>
            <div class="col-md-3"><label class="form-label">Duración en días</label><input type="number" name="duration_days" class="form-control" min="1" value="{{ old('duration_days', $plan->duration_days) }}" required></div>
            <div class="col-md-3"><label class="form-label">Precio</label><input type="number" step="0.01" name="price" class="form-control" min="0" value="{{ old('price', $plan->price) }}" required></div>
            <div class="col-12"><label class="form-label">Descripción</label><textarea name="description" class="form-control" rows="3">{{ old('description', $plan->description) }}</textarea></div>
            <input type="hidden" name="is_active" value="{{ $plan->is_active ? 1 : 0 }}">
            <div class="col-12 d-flex gap-2">
                <button class="btn btn-primary">Actualizar</button>
                <a href="{{ route('membership-plans.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection