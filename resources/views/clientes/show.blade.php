@extends('layouts.app')

@section('titulo', $cliente->nombres . ' ' . $cliente->apellidos)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">{{ $cliente->nombres }} {{ $cliente->apellidos }}</h1>
        <span class="badge fs-6 {{ $cliente->estado === 'Activo' ? 'bg-success' : 'bg-secondary' }}">
            {{ $cliente->estado }}
        </span>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4"><strong>Documento:</strong> {{ $cliente->documento_identidad }}</div>
                <div class="col-md-4"><strong>Teléfono:</strong> {{ $cliente->telefono ?? '—' }}</div>
                <div class="col-md-4"><strong>Correo:</strong> {{ $cliente->correo ?? '—' }}</div>
                <div class="col-12 mt-2"><strong>Dirección:</strong> {{ $cliente->direccion ?? '—' }}</div>
            </div>
        </div>
    </div>

    <h2 class="h5 mb-3">Historial de créditos</h2>
    <div class="card shadow-sm">
        <table class="table mb-0 align-middle">
            <thead class="table-light">
                <tr><th>Fecha</th><th>Monto</th><th>Total</th><th>Saldo</th><th>Estado</th></tr>
            </thead>
            <tbody>
            @forelse ($cliente->creditos as $credito)
                <tr>
                    <td>{{ $credito->fecha_otorgamiento }}</td>
                    <td>{{ $credito->monto }}</td>
                    <td>{{ $credito->total_credito }}</td>
                    <td>{{ $credito->saldo }}</td>
                    <td>{{ $credito->estado }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">Este cliente no tiene créditos registrados.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary mt-4">Volver al listado</a>
@endsection