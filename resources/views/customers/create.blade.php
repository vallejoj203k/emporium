@extends('layouts.app')

@section('title', 'Nuevo cliente')

@section('content')
<div class="card">
    <div class="card-body">
        <h1 class="h4 mb-4">Registrar cliente</h1>
        <form method="POST" action="{{ route('customers.store') }}" class="row g-3">
            @csrf
            <div class="col-md-4">
                <label class="form-label">Tipo de documento</label>
                <select name="document_type_id" class="form-select" required>
                    <option value="">Selecciona</option>
                    @foreach($documentTypes as $documentType)
                    <option value="{{ $documentType->id }}">{{ $documentType->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4"><label class="form-label">Nombres</label><input name="first_name" class="form-control" required></div>
            <div class="col-md-4"><label class="form-label">Apellidos</label><input name="last_name" class="form-control" required></div>
            <div class="col-md-4"><label class="form-label">Número de documento</label><input name="document_number" class="form-control" required></div>
            <div class="col-md-4"><label class="form-label">Fecha de nacimiento</label><input type="date" name="birth_date" class="form-control"></div>
            <div class="col-md-4">
                <label class="form-label">Género</label>
                <select name="gender" class="form-select">
                    <option value="">Selecciona</option>
                    <option value="male">Masculino</option>
                    <option value="female">Femenino</option>
                    <option value="other">Otro</option>
                </select>
            </div>
            <div class="col-md-4"><label class="form-label">Teléfono</label><input name="phone" class="form-control"></div>
            <div class="col-md-4"><label class="form-label">Contacto de emergencia</label><input name="emergency_contact_name" class="form-control"></div>
            <div class="col-md-4"><label class="form-label">Teléfono emergencia</label><input name="emergency_contact_phone" class="form-control"></div>
            <div class="col-md-4"><label class="form-label">Fecha de inscripción</label><input type="date" name="registered_at" class="form-control" value="{{ now()->toDateString() }}" required></div>
            <div class="col-md-4">
                <label class="form-label">Estado</label>
                <select name="status" class="form-select">
                    <option value="active">Activo</option>
                    <option value="suspended">Suspendido</option>
                    <option value="expired">Vencido</option>
                </select>
            </div>
            <div class="col-12"><label class="form-label">Observaciones</label><textarea name="observations" class="form-control" rows="4"></textarea></div>
            <div class="col-12 d-flex gap-2">
                <button class="btn btn-primary">Guardar</button>
                <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection