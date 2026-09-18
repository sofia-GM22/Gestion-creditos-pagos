@extends('layouts.app')

@section('titulo', 'Créditos')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Créditos</h1>
        <a href="{{ route('creditos.create') }}" class="btn btn-success">+ Nuevo crédito</a>
    </div>

    <form method="GET" class="row g-2 mb-3">
        <div class="col-auto">
            <input type="text" name="buscar" value="{{ request('buscar') }}"
                   class="form-control" placeholder="Buscar por cliente o documento">
        </div>
        <div class="col-auto">
            <select name="estado" class="form-select">
                <option value="">Todos los estados</option>
                <option value="Activo" @selected(request('estado')=='Activo')>Activo</option>
                <option value="Pagado" @selected(request('estado')=='Pagado')>Pagado</option>
                <option value="Vencido" @selected(request('estado')=='Vencido')>Vencido</option>
            </select>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-outline-primary">Buscar</button>
        </div>
    </form>

    <div class="card shadow-sm">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Cliente</th>
                    <th>Otorgado</th>
                    <th>Vence</th>
                    <th>Total</th>
                    <th>Saldo</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($creditos as $credito)
                <tr>
                    <td>{{ $credito->cliente->nombres }} {{ $credito->cliente->apellidos }}</td>
                    <td>{{ $credito->fecha_otorgamiento->format('d/m/Y') }}</td>
                    <td>{{ $credito->fecha_vencimiento->format('d/m/Y') }}</td>
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
                    <td colspan="7" class="text-center text-muted py-4">No hay créditos registrados todavía.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $creditos->links() }}
    </div>
@endsection
