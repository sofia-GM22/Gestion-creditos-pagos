@extends('layouts.app')

@section('titulo', 'Mis Créditos')

@section('content')
    <h1 class="h3 mb-4">Mis Créditos</h1>

    <div class="card shadow-sm">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Otorgado</th>
                    <th>Plazo</th>
                    <th>Vence</th>
                    <th>Monto</th>
                    <th>Total</th>
                    <th>Saldo</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($creditos as $credito)
                <tr>
                    <td>{{ $credito->fecha_otorgamiento->format('d/m/Y') }}</td>
                    <td>{{ $credito->plazo }} meses</td>
                    <td>{{ $credito->fecha_vencimiento->format('d/m/Y') }}</td>
                    <td>${{ number_format($credito->monto, 2) }}</td>
                    <td>${{ number_format($credito->total_credito, 2) }}</td>
                    <td>${{ number_format($credito->saldo, 2) }}</td>
                    <td>
                        <span class="badge {{ $credito->estado === 'Activo' ? 'bg-primary' : ($credito->estado === 'Pagado' ? 'bg-success' : 'bg-danger') }}">
                            {{ $credito->estado }}
                        </span>
                    </td>
                    <td class="text-end">
                        <a href="{{ route('creditos.show', $credito) }}" class="btn btn-sm btn-outline-secondary">Ver</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">No tienes créditos registrados todavía.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
