@extends('layouts.app')

@section('titulo', 'Crédito #'.$credito->id)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Crédito de {{ $credito->cliente->nombres }} {{ $credito->cliente->apellidos }}</h1>
        <span class="badge fs-6 {{ $credito->estado === 'Activo' ? 'bg-primary' : ($credito->estado === 'Pagado' ? 'bg-success' : 'bg-danger') }}">
            {{ $credito->estado }}
        </span>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3"><strong>Otorgado:</strong><br>{{ $credito->fecha_otorgamiento->format('d/m/Y') }}</div>
                <div class="col-md-3"><strong>Vence:</strong><br>{{ $credito->fecha_vencimiento->format('d/m/Y') }}</div>
                <div class="col-md-3"><strong>Plazo:</strong><br>{{ $credito->plazo }} meses</div>
                <div class="col-md-3"><strong>Tasa de interés:</strong><br>{{ $credito->tasa_interes }}%</div>
                <div class="col-md-3"><strong>Monto original:</strong><br>${{ number_format($credito->monto, 2) }}</div>
                <div class="col-md-3"><strong>Total del crédito:</strong><br>${{ number_format($credito->total_credito, 2) }}</div>
                <div class="col-md-3"><strong>Saldo pendiente:</strong><br><span class="fw-bold">${{ number_format($credito->saldo, 2) }}</span></div>
                @unless (auth()->user()->esCliente())
                    <div class="col-md-3">
                        <strong>Cliente:</strong><br>
                        <a href="{{ route('clientes.show', $credito->cliente) }}">{{ $credito->cliente->documento_identidad }}</a>
                    </div>
                @endunless
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h5 mb-0">Historial de pagos</h2>
        @if ($credito->saldo > 0 && ! auth()->user()->esCliente())
            <a href="{{ route('pagos.create', $credito) }}" class="btn btn-success btn-sm">+ Registrar pago</a>
        @endif
    </div>

    <div class="card shadow-sm">
        <table class="table mb-0 align-middle">
            <thead class="table-light">
                <tr><th>Fecha</th><th>Monto</th><th>Referencia</th><th>Observaciones</th><th class="text-end">Comprobante</th></tr>
            </thead>
            <tbody>
            @forelse ($credito->pagos as $pago)
                <tr>
                    <td>{{ $pago->fecha_pago->format('d/m/Y') }}</td>
                    <td>${{ number_format($pago->monto, 2) }}</td>
                    <td>{{ $pago->referencia ?? '—' }}</td>
                    <td>{{ $pago->observaciones ?? '—' }}</td>
                    <td class="text-end">
                        <a href="{{ route('pagos.show', $pago) }}" class="btn btn-sm btn-outline-secondary">Ver</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">Este crédito no tiene pagos registrados todavía.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <a href="{{ auth()->user()->esCliente() ? route('creditos.mios') : route('creditos.index') }}"
       class="btn btn-outline-secondary mt-4">Volver</a>
@endsection
