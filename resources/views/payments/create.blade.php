@extends('layouts.app')

@section('title', 'Registrar pago')

@section('content')
<div class="card">
    <div class="card-body">
        <h1 class="h4 mb-4">Registrar pago</h1>
        <form method="POST" action="{{ route('payments.store') }}" class="row g-3">
            @csrf
            <div class="col-md-4">
                <label class="form-label">Cliente</label>
                <select name="customer_id" class="form-select" required>
                    <option value="">Selecciona</option>
                    @foreach($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->first_name }} {{ $customer->last_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Membresía asociada</label>
                <select name="customer_membership_id" class="form-select">
                    <option value="">Sin asociar</option>
                    @foreach($memberships as $membership)
                    <option value="{{ $membership->id }}">{{ $membership->customer->first_name }} {{ $membership->customer->last_name }} - {{ $membership->plan->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Método de pago</label>
                <select name="payment_method_id" class="form-select" required>
                    <option value="">Selecciona</option>
                    @foreach($paymentMethods as $paymentMethod)
                    <option value="{{ $paymentMethod->id }}">{{ $paymentMethod->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4"><label class="form-label">Valor</label><input type="number" step="0.01" min="0" name="amount" class="form-control" required></div>
            <div class="col-md-4"><label class="form-label">Fecha de pago</label><input type="date" name="payment_date" class="form-control" value="{{ now()->toDateString() }}" required></div>
            <div class="col-md-4"><label class="form-label">Número de comprobante</label><input name="receipt_number" class="form-control"></div>
            <div class="col-12"><label class="form-label">Observaciones</label><textarea name="observations" class="form-control" rows="3"></textarea></div>
            <div class="col-12 d-flex gap-2">
                <button class="btn btn-primary">Guardar</button>
                <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection