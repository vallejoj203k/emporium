@extends('layouts.app')

@section('title', 'Nuevo cliente')

@section('content')
<div class="card">
    <div class="card-body">
        <h1 class="h4 mb-4">Registrar cliente</h1>

        @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('customers.store') }}" class="row g-3">
            @csrf
            <div class="col-md-4">
                <label class="form-label">Tipo de documento</label>
                <select name="document_type_id" class="form-select" required>
                    <option value="">Selecciona</option>
                    @foreach($documentTypes as $documentType)
                    <option value="{{ $documentType->id }}" @selected(old('document_type_id') == $documentType->id)>{{ $documentType->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4"><label class="form-label">Nombres</label><input name="first_name" class="form-control" value="{{ old('first_name') }}" required></div>
            <div class="col-md-4"><label class="form-label">Apellidos</label><input name="last_name" class="form-control" value="{{ old('last_name') }}" required></div>
            <div class="col-md-4"><label class="form-label">Número de documento</label><input name="document_number" class="form-control" value="{{ old('document_number') }}" required></div>
            <div class="col-md-4"><label class="form-label">Fecha de nacimiento</label><input type="date" name="birth_date" class="form-control" value="{{ old('birth_date') }}"></div>
            <div class="col-md-4">
                <label class="form-label">Género</label>
                <select name="gender" class="form-select">
                    <option value="">Selecciona</option>
                    <option value="male" @selected(old('gender') === 'male')>Masculino</option>
                    <option value="female" @selected(old('gender') === 'female')>Femenino</option>
                    <option value="other" @selected(old('gender') === 'other')>Otro</option>
                </select>
            </div>
            <div class="col-md-4"><label class="form-label">Teléfono</label><input name="phone" class="form-control" value="{{ old('phone') }}"></div>
            <div class="col-md-4"><label class="form-label">Contacto de emergencia</label><input name="emergency_contact_name" class="form-control" value="{{ old('emergency_contact_name') }}"></div>
            <div class="col-md-4"><label class="form-label">Teléfono emergencia</label><input name="emergency_contact_phone" class="form-control" value="{{ old('emergency_contact_phone') }}"></div>
            <div class="col-md-4"><label class="form-label">Fecha de inscripción</label><input type="date" name="registered_at" class="form-control" value="{{ old('registered_at', now()->toDateString()) }}" required></div>
            <div class="col-md-4">
                <label class="form-label">Estado</label>
                <select name="status" class="form-select">
                    <option value="active" @selected(old('status', 'active') === 'active')>Activo</option>
                    <option value="suspended" @selected(old('status') === 'suspended')>Suspendido</option>
                    <option value="expired" @selected(old('status') === 'expired')>Vencido</option>
                </select>
            </div>
            <div class="col-12"><label class="form-label">Observaciones</label><textarea name="observations" class="form-control" rows="4">{{ old('observations') }}</textarea></div>

            <div class="col-12">
                <hr class="my-2">
                <h2 class="h6 mb-1">Membresía inicial <span class="text-muted fw-normal">(opcional)</span></h2>
                <p class="text-muted small mb-0">Si eliges un plan, se registrará la membresía y el ingreso del pago automáticamente.</p>
            </div>
            <div class="col-md-6">
                <label class="form-label">Plan</label>
                <select name="membership_plan_id" id="membership_plan_id" class="form-select">
                    <option value="" data-price="" data-duration="">Sin plan</option>
                    @foreach($plans as $plan)
                    <option value="{{ $plan->id }}"
                            data-price="{{ $plan->price }}"
                            data-duration="{{ $plan->duration_days }}"
                            @selected(old('membership_plan_id') == $plan->id)>
                        {{ $plan->name }} — {{ $plan->duration_days }} días — ${{ number_format($plan->price, 0, ',', '.') }}
                    </option>
                    @endforeach
                </select>
                <div id="plan_info" class="form-text"></div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Método de pago</label>
                <select name="payment_method_id" id="payment_method_id" class="form-select">
                    <option value="">Selecciona</option>
                    @foreach($paymentMethods as $paymentMethod)
                    <option value="{{ $paymentMethod->id }}" @selected(old('payment_method_id') == $paymentMethod->id)>{{ $paymentMethod->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4"><label class="form-label">Fecha de inicio</label><input type="date" name="start_date" id="start_date" class="form-control" value="{{ old('start_date', now()->toDateString()) }}"></div>
            <div class="col-md-4"><label class="form-label">Valor pagado (ingreso)</label><input type="number" step="0.01" min="0" name="paid_amount" id="paid_amount" class="form-control" value="{{ old('paid_amount') }}"></div>
            <div class="col-md-4"><label class="form-label">Número de comprobante</label><input name="receipt_number" class="form-control" value="{{ old('receipt_number') }}"></div>
            <div class="col-12"><label class="form-label">Observaciones de la membresía</label><textarea name="membership_observations" class="form-control" rows="2">{{ old('membership_observations') }}</textarea></div>

            <div class="col-12 d-flex gap-2">
                <button class="btn btn-primary">Guardar</button>
                <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<script>
    (function () {
        const planSelect = document.getElementById('membership_plan_id');
        const paidAmount = document.getElementById('paid_amount');
        const startDate = document.getElementById('start_date');
        const planInfo = document.getElementById('plan_info');

        function selectedOption() {
            return planSelect.options[planSelect.selectedIndex];
        }

        function formatDate(date) {
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            return `${day}/${month}/${date.getFullYear()}`;
        }

        function formatMoney(value) {
            return '$' + Math.round(value).toLocaleString('es-CO');
        }

        function refresh(autofill) {
            const option = selectedOption();
            const price = parseFloat(option.dataset.price);
            const duration = parseInt(option.dataset.duration, 10);

            if (!option.value || isNaN(duration)) {
                planInfo.textContent = '';
                return;
            }

            // Autocompleta el valor a pagar con el precio del plan (solo al cambiar de plan).
            if (autofill && !isNaN(price)) {
                paidAmount.value = price;
            }

            let info = `Duración: ${duration} día(s) · Precio: ${formatMoney(price)}`;

            if (startDate.value) {
                const end = new Date(startDate.value + 'T00:00:00');
                end.setDate(end.getDate() + Math.max(duration - 1, 0));
                info += ` · Vence el ${formatDate(end)}`;
            }

            planInfo.textContent = info;
        }

        planSelect.addEventListener('change', () => refresh(true));
        startDate.addEventListener('change', () => refresh(false));
        refresh(false);
    })();
</script>
@endsection
