@extends('layouts.app')

@section('title', 'Registrar membresía')

@section('content')
<div class="card">
    <div class="card-body">
        <h1 class="h4 mb-4">Registrar membresía</h1>
        <form method="POST" action="{{ route('customer-memberships.store') }}" class="row g-3">
            @csrf
            <div class="col-md-6">
                <label class="form-label">Cliente</label>
                <select name="customer_id" class="form-select" required>
                    <option value="">Selecciona</option>
                    @foreach($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->first_name }} {{ $customer->last_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Plan</label>
                <select name="membership_plan_id" class="form-select" required>
                    <option value="">Selecciona</option>
                    @foreach($plans as $plan)
                    <option value="{{ $plan->id }}">{{ $plan->name }} - {{ $plan->duration_days }} días - ${{ number_format($plan->price, 0, ',', '.') }}</option>
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
            <div class="col-md-4"><label class="form-label">Fecha de inicio</label><input type="date" name="start_date" class="form-control" value="{{ now()->toDateString() }}" required></div>
            <div class="col-md-4"><label class="form-label">Valor pagado</label><input type="number" step="0.01" min="0" name="paid_amount" class="form-control" required></div>
            <div class="col-md-6"><label class="form-label">Número de comprobante</label><input name="receipt_number" class="form-control"></div>
            <div class="col-12"><label class="form-label">Observaciones</label><textarea name="observations" class="form-control" rows="3"></textarea></div>
            <div class="col-12 d-flex gap-2">
                <button class="btn btn-primary">Guardar</button>
                <a href="{{ route('customer-memberships.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection