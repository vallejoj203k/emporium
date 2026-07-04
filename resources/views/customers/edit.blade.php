@extends('layouts.app')

@section('title', 'Editar cliente')

@section('content')
<div class="card">
    <div class="card-body">
        <h1 class="h4 mb-4">Editar cliente</h1>
        <form method="POST" action="{{ route('customers.update', $customer) }}" class="row g-3">
            @csrf
            @method('PUT')
            <div class="col-md-4">
                <label class="form-label">Tipo de documento</label>
                <select name="document_type_id" class="form-select" required>
                    <option value="">Selecciona</option>
                    @foreach($documentTypes as $documentType)
                    <option value="{{ $documentType->id }}" @selected(old('document_type_id', $customer->document_type_id) == $documentType->id)>{{ $documentType->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4"><label class="form-label">Nombres</label><input name="first_name" class="form-control" value="{{ old('first_name', $customer->first_name) }}" required></div>
            <div class="col-md-4"><label class="form-label">Apellidos</label><input name="last_name" class="form-control" value="{{ old('last_name', $customer->last_name) }}" required></div>
            <div class="col-md-4"><label class="form-label">Número de documento</label><input name="document_number" class="form-control" value="{{ old('document_number', $customer->document_number) }}" required></div>
            <div class="col-md-4"><label class="form-label">Fecha de nacimiento</label><input type="date" name="birth_date" class="form-control" value="{{ old('birth_date', optional($customer->birth_date)->toDateString()) }}"></div>
            <div class="col-md-4">
                <label class="form-label">Género</label>
                <select name="gender" class="form-select">
                    <option value="">Selecciona</option>
                    <option value="male" @selected(old('gender', $customer->gender) === 'male')>Masculino</option>
                    <option value="female" @selected(old('gender', $customer->gender) === 'female')>Femenino</option>
                    <option value="other" @selected(old('gender', $customer->gender) === 'other')>Otro</option>
                </select>
            </div>
            <div class="col-md-4"><label class="form-label">Teléfono</label><input name="phone" class="form-control" value="{{ old('phone', $customer->phone) }}"></div>
            <div class="col-md-4"><label class="form-label">Contacto de emergencia</label><input name="emergency_contact_name" class="form-control" value="{{ old('emergency_contact_name', $customer->emergency_contact_name) }}"></div>
            <div class="col-md-4"><label class="form-label">Teléfono emergencia</label><input name="emergency_contact_phone" class="form-control" value="{{ old('emergency_contact_phone', $customer->emergency_contact_phone) }}"></div>
            <div class="col-md-4"><label class="form-label">Fecha de inscripción</label><input type="date" name="registered_at" class="form-control" value="{{ old('registered_at', optional($customer->registered_at)->toDateString()) }}" required></div>
            <div class="col-md-4">
                <label class="form-label">Estado</label>
                <select name="status" class="form-select">
                    <option value="active" @selected(old('status', $customer->status) === 'active')>Activo</option>
                    <option value="suspended" @selected(old('status', $customer->status) === 'suspended')>Suspendido</option>
                    <option value="expired" @selected(old('status', $customer->status) === 'expired')>Vencido</option>
                </select>
            </div>
            <div class="col-12"><label class="form-label">Observaciones</label><textarea name="observations" class="form-control" rows="4">{{ old('observations', $customer->observations) }}</textarea></div>
            <div class="col-12 d-flex gap-2">
                <button class="btn btn-primary">Actualizar</button>
                <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection