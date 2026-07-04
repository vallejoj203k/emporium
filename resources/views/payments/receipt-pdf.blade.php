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

        .box {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
        }

        .title {
            font-size: 18px;
            font-weight: 700;
        }

        .muted {
            color: #666;
        }

        .row {
            width: 100%;
        }

        .row:after {
            content: "";
            display: block;
            clear: both;
        }

        .col {
            width: 48%;
            float: left;
            margin-right: 2%;
            margin-bottom: 8px;
        }

        .label {
            color: #666;
            font-size: 10px;
            display: block;
        }

        .value {
            font-size: 13px;
            font-weight: 700;
        }
    </style>
</head>

<body>
    <div class="title">Comprobante de pago</div>
    <div class="muted">{{ $payment->receipt_number }}</div>

    <div class="row" style="margin-top: 16px;">
        <div class="col box"><span class="label">Cliente</span>
            <div class="value">{{ $payment->customer->first_name }} {{ $payment->customer->last_name }}</div>
        </div>
        <div class="col box"><span class="label">Documento</span>
            <div class="value">{{ $payment->customer->documentType?->code }} {{ $payment->customer->document_number }}</div>
        </div>
        <div class="col box"><span class="label">Fecha</span>
            <div class="value">{{ $payment->payment_date?->format('d/m/Y') }}</div>
        </div>
        <div class="col box"><span class="label">Método</span>
            <div class="value">{{ $payment->method->name }}</div>
        </div>
        <div class="col box"><span class="label">Valor</span>
            <div class="value">${{ number_format($payment->amount, 0, ',', '.') }}</div>
        </div>
        <div class="col box"><span class="label">Registrado por</span>
            <div class="value">{{ $payment->user->name }}</div>
        </div>
    </div>

    <div class="box">
        <span class="label">Membresía asociada</span>
        <div class="value">
            @if($payment->membership)
            {{ $payment->membership->plan->name }} · {{ $payment->membership->start_date?->format('d/m/Y') }} al {{ $payment->membership->end_date?->format('d/m/Y') }}
            @else
            Pago no asociado a una membresía específica.
            @endif
        </div>
    </div>

    <div class="box">
        <span class="label">Observaciones</span>
        <div class="value">{{ $payment->observations ?? 'Sin observaciones' }}</div>
    </div>
</body>

</html>