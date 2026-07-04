<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #111;
        }

        h1,
        h2 {
            margin: 0 0 8px 0;
        }

        .muted {
            color: #666;
        }

        .section {
            margin-bottom: 18px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }

        th {
            background: #f3f4f6;
        }

        .grid {
            width: 100%;
        }

        .card {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 8px;
        }

        .row {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .col {
            flex: 1 1 220px;
        }
    </style>
</head>

<body>
    <h1>Reporte del Gimnasio</h1>
    <div class="muted">Rango: {{ $startDate }} a {{ $endDate }}</div>

    <div class="section row">
        <div class="col card">Clientes activos: <strong>{{ $activeCustomers }}</strong></div>
        <div class="col card">Clientes vencidos: <strong>{{ $expiredCustomers }}</strong></div>
        <div class="col card">Próximos a vencer: <strong>{{ $upcomingCustomers }}</strong></div>
        <div class="col card">Nuevos en rango: <strong>{{ $newCustomers }}</strong></div>
    </div>

    <div class="section row">
        <div class="col card">Ingresos diarios: <strong>${{ number_format($dailyIncome, 0, ',', '.') }}</strong></div>
        <div class="col card">Ingresos mensuales: <strong>${{ number_format($monthlyIncome, 0, ',', '.') }}</strong></div>
        <div class="col card">Ingresos anuales: <strong>${{ number_format($yearlyIncome, 0, ',', '.') }}</strong></div>
    </div>

    <div class="section">
        <h2>Clientes activos</h2>
        <table>
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Documento</th>
                    <th>Teléfono</th>
                </tr>
            </thead>
            <tbody>
                @forelse($activeCustomersList as $customer)
                <tr>
                    <td>{{ $customer->first_name }} {{ $customer->last_name }}</td>
                    <td>{{ $customer->documentType?->code }} {{ $customer->document_number }}</td>
                    <td>{{ $customer->phone ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3">Sin datos.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>Clientes vencidos</h2>
        <table>
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Documento</th>
                    <th>Teléfono</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expiredCustomersList as $customer)
                <tr>
                    <td>{{ $customer->first_name }} {{ $customer->last_name }}</td>
                    <td>{{ $customer->documentType?->code }} {{ $customer->document_number }}</td>
                    <td>{{ $customer->phone ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3">Sin datos.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>Membresías próximas a vencer</h2>
        <table>
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
                    <td colspan="3">Sin datos.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>Pagos recientes</h2>
        <table>
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Fecha</th>
                    <th>Valor</th>
                    <th>Método</th>
                    <th>Comprobante</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentPayments as $payment)
                <tr>
                    <td>{{ $payment->customer->first_name }} {{ $payment->customer->last_name }}</td>
                    <td>{{ $payment->payment_date?->format('d/m/Y') }}</td>
                    <td>${{ number_format($payment->amount, 0, ',', '.') }}</td>
                    <td>{{ $payment->method->name }}</td>
                    <td>{{ $payment->receipt_number ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">Sin datos.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>

</html>