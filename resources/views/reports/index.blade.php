@extends('layouts.app')

@section('title', 'Reportes')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h1 class="h3 mb-1">Reportes</h1>
        <p class="text-muted mb-0">Resumen operativo y financiero del gimnasio</p>
    </div>
    <div class="d-flex flex-column flex-md-row gap-2">
        <a href="{{ route('reports.export.excel', request()->query()) }}" class="btn btn-success">Exportar Excel</a>
        <a href="{{ route('reports.export.pdf', request()->query()) }}" class="btn btn-danger">Exportar PDF</a>
    </div>
</div>

<form class="row g-2 align-items-end mb-4" method="GET" action="{{ route('reports.index') }}">
    <div class="col-auto">
        <label class="form-label small mb-1">Desde</label>
        <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
    </div>
    <div class="col-auto">
        <label class="form-label small mb-1">Hasta</label>
        <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
    </div>
    <div class="col-auto">
        <button class="btn btn-primary">Filtrar</button>
    </div>
</form>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="text-muted small">Activos</div>
                <div class="h3 mb-0">{{ $activeCustomers }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="text-muted small">Vencidos</div>
                <div class="h3 mb-0">{{ $expiredCustomers }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="text-muted small">Próximos a vencer</div>
                <div class="h3 mb-0">{{ $upcomingCustomers }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="text-muted small">Nuevos en rango</div>
                <div class="h3 mb-0">{{ $newCustomers }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-4">
        <div class="card">
            <div class="card-body">
                <div class="text-muted small">Ingresos diarios</div>
                <div class="h4 mb-0">${{ number_format($dailyIncome, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-4">
        <div class="card">
            <div class="card-body">
                <div class="text-muted small">Ingresos mensuales</div>
                <div class="h4 mb-0">${{ number_format($monthlyIncome, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="card">
            <div class="card-body">
                <div class="text-muted small">Ingresos anuales</div>
                <div class="h4 mb-0">${{ number_format($yearlyIncome, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-body">
                <h2 class="h5">Clientes activos</h2>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Cliente</th>
                                <th>Documento</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activeCustomersList as $customer)
                            <tr>
                                <td>{{ $customer->first_name }} {{ $customer->last_name }}</td>
                                <td>{{ $customer->documentType?->code }} {{ $customer->document_number }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-muted">Sin datos.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-body">
                <h2 class="h5">Clientes vencidos</h2>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Cliente</th>
                                <th>Documento</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($expiredCustomersList as $customer)
                            <tr>
                                <td>{{ $customer->first_name }} {{ $customer->last_name }}</td>
                                <td>{{ $customer->documentType?->code }} {{ $customer->document_number }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-muted">Sin datos.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-body">
                <h2 class="h5">Membresías próximas a vencer</h2>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Cliente</th>
                                <th>Plan</th>
                                <th>Vence</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($upcomingMemberships as $membership)
                            <tr>
                                <td>{{ $membership->customer->first_name }} {{ $membership->customer->last_name }}</td>
                                <td>{{ $membership->plan->name }}</td>
                                <td>{{ $membership->end_date?->format('d/m/Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-muted">Sin datos.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-body">
                <h2 class="h5">Membresías más vendidas</h2>
                <ul class="list-group list-group-flush">
                    @forelse($topPlans as $plan)
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span>{{ $plan->name }}</span>
                        <strong>{{ $plan->total }}</strong>
                    </li>
                    @empty
                    <li class="list-group-item px-0 text-muted">Aún no hay datos.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-body">
                <h2 class="h5">Ingresos por día</h2>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($incomeByDay as $row)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($row->day)->format('d/m/Y') }}</td>
                                <td>${{ number_format($row->total, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-muted">Sin datos.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-body">
                <h2 class="h5">Pagos recientes</h2>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Cliente</th>
                                <th>Fecha</th>
                                <th>Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPayments as $payment)
                            <tr>
                                <td>{{ $payment->customer->first_name }} {{ $payment->customer->last_name }}</td>
                                <td>{{ $payment->payment_date?->format('d/m/Y') }}</td>
                                <td>${{ number_format($payment->amount, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-muted">Sin datos.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection