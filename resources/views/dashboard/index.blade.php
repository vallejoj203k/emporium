@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
    <div>
        <h1 class="h3 mb-1">Dashboard</h1>
        <p class="text-muted mb-0">Resumen operativo del gimnasio</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="text-muted small">Clientes activos</div>
                <div class="h3 mb-0">{{ $activeCustomers }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="text-muted small">Clientes vencidos</div>
                <div class="h3 mb-0">{{ $expiredCustomers }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="text-muted small">Membresías activas</div>
                <div class="h3 mb-0">{{ $activeMemberships }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="text-muted small">Membresías vencidas</div>
                <div class="h3 mb-0">{{ $expiredMemberships }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="text-muted small">Vencen hoy</div>
                <div class="h3 mb-0">{{ $expiringToday }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="text-muted small">Vencen en 3 días</div>
                <div class="h3 mb-0">{{ $expiringIn3Days }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="text-muted small">Nuevos del mes</div>
                <div class="h3 mb-0">{{ $newCustomersMonth }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="text-muted small">Ingresos del día</div>
                <div class="h3 mb-0">${{ number_format($incomeToday, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="text-muted small">Ingresos del mes</div>
                <div class="h3 mb-0">${{ number_format($incomeMonth, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="text-muted small">Pagos hoy</div>
                <div class="h3 mb-0">{{ $paymentsToday }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="text-muted small">Pagos este mes</div>
                <div class="h3 mb-0">{{ $paymentsMonth }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-body">
                <h2 class="h5">Alertas recientes</h2>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Cliente</th>
                                <th>Tipo</th>
                                <th>Mensaje</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($alerts as $alert)
                            <tr>
                                <td>{{ $alert->customer?->first_name }} {{ $alert->customer?->last_name }}</td>
                                <td>{{ $alert->type }}</td>
                                <td>{{ $alert->message }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-muted">Sin alertas pendientes.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-body">
                <div class="text-muted small">Total esperado en caja</div>
                <div class="h3 mb-0">${{ number_format($expectedCash, 0, ',', '.') }}</div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h2 class="h5">Planes más vendidos</h2>
                <ul class="list-group list-group-flush">
                    @forelse($topMembershipPlans as $row)
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span>{{ $row->name }}</span>
                        <strong>{{ $row->total }}</strong>
                    </li>
                    @empty
                    <li class="list-group-item px-0 text-muted">Aún no hay datos.</li>
                    @endforelse
                </ul>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-body">
                <h2 class="h5">Clientes nuevos por mes</h2>
                <canvas id="newCustomersChart" height="180"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mt-1">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h2 class="h5">Ingresos por día</h2>
                <canvas id="incomeChart" height="120"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
    const incomeCtx = document.getElementById('incomeChart');
    if (incomeCtx) {
        new Chart(incomeCtx, {
            type: 'line',
            data: {
                labels: @json($incomeChartLabels),
                datasets: [{
                    label: 'Ingresos',
                    data: @json($incomeChartValues),
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.12)',
                    tension: 0.35,
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }

    const newCustomersCtx = document.getElementById('newCustomersChart');
    if (newCustomersCtx) {
        new Chart(newCustomersCtx, {
            type: 'bar',
            data: {
                labels: @json($newCustomerChartLabels),
                datasets: [{
                    label: 'Clientes',
                    data: @json($newCustomerChartValues),
                    borderWidth: 0,
                    backgroundColor: '#198754',
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }
</script>
@endsection