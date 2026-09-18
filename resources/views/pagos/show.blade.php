@extends('layouts.app')

@section('titulo', 'Comprobante de Pago #'.$pago->id)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
        <h1 class="h3 mb-0">Comprobante de Pago</h1>
        <button onclick="window.print()" class="btn btn-outline-primary">Imprimir</button>
    </div>

    <div class="card shadow-sm mx-auto" style="max-width: 600px;">
        <div class="card-body">
            <h2 class="h5 text-center mb-1">Sistema de Gestión de Créditos</h2>
            <p class="text-center text-muted mb-4">Comprobante de pago #{{ $pago->id }}</p>

            <table class="table table-borderless mb-0">
                <tr><th style="width: 40%">Cliente:</th><td>{{ $pago->credito->cliente->nombres }} {{ $pago->credito->cliente->apellidos }}</td></tr>
                <tr><th>Documento:</th><td>{{ $pago->credito->cliente->documento_identidad }}</td></tr>
                <tr><th>Crédito:</th><td>#{{ $pago->credito->id }}</td></tr>
                <tr><th>Fecha de pago:</th><td>{{ $pago->fecha_pago->format('d/m/Y') }}</td></tr>
                <tr><th>Monto pagado:</th><td class="fw-bold">${{ number_format($pago->monto, 2) }}</td></tr>
                <tr><th>Referencia:</th><td>{{ $pago->referencia ?? '—' }}</td></tr>
                <tr><th>Observaciones:</th><td>{{ $pago->observaciones ?? '—' }}</td></tr>
                <tr><th>Saldo restante:</th><td>${{ number_format($pago->credito->saldo, 2) }}</td></tr>
                <tr><th>Estado del crédito:</th><td>{{ $pago->credito->estado }}</td></tr>
            </table>
        </div>
    </div>

    <div class="text-center mt-4 no-print">
        <a href="{{ route('creditos.show', $pago->credito) }}" class="btn btn-outline-secondary">Volver al crédito</a>
    </div>

    <style>
        @media print {
            .no-print, nav, .alert { display: none !important; }
            body { background: #fff !important; }
        }
    </style>
@endsection
