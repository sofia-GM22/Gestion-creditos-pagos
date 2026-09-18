@extends('layouts.app')

@section('titulo', 'Clientes por estado de crédito')

@section('content')
    <h1 class="h3 mb-4">Clientes por estado de crédito</h1>

    <form method="GET" class="row g-2 mb-3">
        <div class="col-auto">
            <select name="estado_credito" class="form-select" onchange="this.form.submit()">
                <option value="Activo" @selected($estado=='Activo')>Activo</option>
                <option value="Pagado" @selected($estado=='Pagado')>Pagado</option>
                <option value="Vencido" @selected($estado=='Vencido')>Vencido</option>
            </select>
        </div>
    </form>

    <div class="card shadow-sm">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr><th>Cliente</th><th>Documento</th><th>Estado</th></tr>
            </thead>
            <tbody>
            @forelse ($clientes as $cliente)
                <tr>
                    <td>{{ $cliente->nombres }} {{ $cliente->apellidos }}</td>
                    <td>{{ $cliente->documento_identidad }}</td>
                    <td>
                        <span class="badge bg-info text-dark">{{ $cliente->estado }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center text-muted py-4">No hay clientes con créditos en este estado.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $clientes->links() }}
    </div>
@endsection