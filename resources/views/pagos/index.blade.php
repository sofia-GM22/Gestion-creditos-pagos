@extends('layouts.app')

@section('titulo', 'Pagos')

@section('content')
    <h1 class="h3 mb-4">Historial de Pagos</h1>

    <form method="GET" class="row g-2 mb-3">
        <div class="col-auto">
            <input type="text" name="buscar" value="{{ request('buscar') }}"
                   class="form-control" placeholder="Buscar por nombre del cliente">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-outline-primary">Buscar</button>
        </div>
    </form>

    <div class="card shadow-sm">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Crédito</th>
                    <th>Monto</th>
                    <th>Referencia</th>
                    <th class="text-end">Comprobante</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($pagos as $pago)
                <tr>
                    <td>{{ $pago->fecha_pago->format('d/m/Y') }}</td>
                    <td>{{ $pago->credito->cliente->nombres }} {{ $pago->credito->cliente->apellidos }}</td>
                    <td><a href="{{ route('creditos.show', $pago->credito) }}">#{{ $pago->credito_id }}</a></td>
                    <td>${{ number_format($pago->monto, 2) }}</td>
                    <td>{{ $pago->referencia ?? '—' }}</td>
                    <td class="text-end">
                        <a href="{{ route('pagos.show', $pago) }}" class="btn btn-sm btn-outline-secondary">Ver</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">No hay pagos registrados todavía.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $pagos->links() }}
    </div>
@endsection
