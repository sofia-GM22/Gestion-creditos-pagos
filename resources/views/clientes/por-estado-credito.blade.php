@extends('layouts.app')

@section('titulo', 'Clientes por estado de crédito')

@section('content')

    <div class="page-header">
        <div>
            <span class="section-kicker">
                Seguimiento de cartera
            </span>

            <h1 class="page-title">
                Clientes por estado de crédito
            </h1>

            <p class="page-subtitle">
                Consulta los clientes según el estado actual de sus créditos.
            </p>
        </div>
    </div>

    <div class="card app-card mb-4">

        <div class="card-body p-4">

            <div class="filter-title mb-3">
                Seleccionar estado
            </div>

            <form method="GET" class="row g-3 align-items-end">

                <div class="col-md-5">

                    <label
                        for="estado_credito"
                        class="form-label"
                    >
                        Estado del crédito
                    </label>

                    <select
                        id="estado_credito"
                        name="estado_credito"
                        class="form-select"
                        onchange="this.form.submit()"
                    >

                        <option
                            value="Activo"
                            @selected($estado === 'Activo')
                        >
                            Activo
                        </option>

                        <option
                            value="Pagado"
                            @selected($estado === 'Pagado')
                        >
                            Pagado
                        </option>

                        <option
                            value="Vencido"
                            @selected($estado === 'Vencido')
                        >
                            Vencido
                        </option>

                    </select>

                    <div class="form-text">
                        El listado se actualizará automáticamente al cambiar el estado.
                    </div>

                </div>

            </form>

        </div>

    </div>

    <div class="card app-card">

        <div class="card-header bg-transparent border-0 px-4 pt-4 pb-2">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <h2 class="h5 mb-1">
                        Clientes con crédito {{ $estado }}
                    </h2>

                    <p class="text-muted small mb-0">
                        Clientes que actualmente pertenecen al estado seleccionado.
                    </p>
                </div>

                @php
                    $claseEstado = match ($estado) {
                        'Activo' => 'badge-status-success',
                        'Pagado' => 'badge-status-info',
                        'Vencido' => 'badge-status-danger',
                        default => 'badge-status-muted',
                    };
                @endphp

                <span class="badge-status {{ $claseEstado }}">
                    {{ $clientes->total() }} registrados
                </span>

            </div>

        </div>

        <div class="table-responsive">

            <table class="table app-table table-hover align-middle mb-0">

                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Documento</th>
                        <th>Estado del crédito</th>
                    </tr>
                </thead>

                <tbody>

                @forelse ($clientes as $cliente)

                    <tr>

                        <td>
                            <div class="fw-semibold">
                                {{ $cliente->nombres }}
                                {{ $cliente->apellidos }}
                            </div>

                            <div class="small text-muted">
                                Cliente #{{ $cliente->id }}
                            </div>
                        </td>

                        <td>
                            {{ $cliente->documento_identidad }}
                        </td>

                        <td>
                            <span class="badge-status {{ $claseEstado }}">
                                {{ $estado }}
                            </span>
                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="3">

                            <div class="empty-state">

                                <div class="empty-state-icon">
                                    👥
                                </div>

                                <h3 class="h6">
                                    No hay clientes en este estado
                                </h3>

                                <p>
                                    No existen clientes con créditos
                                    en estado {{ strtolower($estado) }}.
                                </p>

                            </div>

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

        @if ($clientes->hasPages())

            <div class="card-footer bg-transparent border-0 px-4 py-3">
                {{ $clientes->links() }}
            </div>

        @endif

    </div>

@endsection