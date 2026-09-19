@extends('layouts.app')

@section('titulo', 'Créditos')

@section('content')

    <div class="page-header">
        <div>
            <span class="section-kicker">
                Administración financiera
            </span>

            <h1 class="page-title">
                Créditos
            </h1>

            <p class="page-subtitle">
                Consulta, administra y da seguimiento a los créditos registrados.
            </p>
        </div>

        <div>
            <a href="{{ route('creditos.create') }}" class="btn btn-primary">
                + Nuevo crédito
            </a>
        </div>
    </div>

    <div class="card app-card mb-4">
        <div class="card-body p-4">

            <div class="filter-title mb-3">
                Filtrar créditos
            </div>

            <form method="GET" class="row g-3 align-items-end">

                <div class="col-lg-4">
                    <label for="buscar" class="form-label">
                        Buscar cliente
                    </label>

                    <input
                        type="text"
                        id="buscar"
                        name="buscar"
                        value="{{ request('buscar') }}"
                        class="form-control"
                        placeholder="Nombre, apellido o documento"
                    >
                </div>

                <div class="col-lg-3">
                    <label for="estado" class="form-label">
                        Estado
                    </label>

                    <select id="estado" name="estado" class="form-select">
                        <option value="">
                            Todos los estados
                        </option>

                        <option
                            value="Activo"
                            @selected(request('estado') === 'Activo')
                        >
                            Activo
                        </option>

                        <option
                            value="Pagado"
                            @selected(request('estado') === 'Pagado')
                        >
                            Pagado
                        </option>

                        <option
                            value="Vencido"
                            @selected(request('estado') === 'Vencido')
                        >
                            Vencido
                        </option>
                    </select>
                </div>

                <div class="col-lg-3">
                    <label for="fecha" class="form-label">
                        Fecha
                    </label>

                    <input
                        type="date"
                        id="fecha"
                        name="fecha"
                        value="{{ request('fecha') }}"
                        class="form-control"
                    >
                </div>

                <div class="col-lg-2">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            Filtrar
                        </button>

                        @if (request('buscar') || request('estado') || request('fecha'))
                            <a
                                href="{{ route('creditos.index') }}"
                                class="btn btn-outline-secondary"
                            >
                                Limpiar
                            </a>
                        @endif
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
                        Lista de créditos
                    </h2>

                    <p class="text-muted small mb-0">
                        Créditos registrados en el sistema.
                    </p>
                </div>

                <span class="badge-status badge-status-info">
                    {{ $creditos->total() }} registrados
                </span>

            </div>

        </div>

        <div class="table-responsive">

            <table class="table app-table table-hover align-middle mb-0">

                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Monto</th>
                        <th>Total</th>
                        <th>Saldo</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>

                <tbody>

                @forelse ($creditos as $credito)

                    <tr>

                        <td>
                            <div class="fw-semibold">
                                {{ $credito->cliente->nombres }}
                                {{ $credito->cliente->apellidos }}
                            </div>

                            <div class="small text-muted">
                                Crédito #{{ $credito->id }}
                            </div>
                        </td>

                        <td>
                            ${{ number_format($credito->monto, 2) }}
                        </td>

                        <td class="fw-semibold">
                            ${{ number_format($credito->total_credito, 2) }}
                        </td>

                        <td>
                            <span class="{{ $credito->saldo > 0 ? 'fw-semibold' : 'text-success fw-semibold' }}">
                                ${{ number_format($credito->saldo, 2) }}
                            </span>
                        </td>

                        <td>
                            {{ \Carbon\Carbon::parse($credito->fecha_otorgamiento)->format('d/m/Y') }}
                        </td>

                        <td>

                            @php
                                $claseEstado = match ($credito->estado) {
                                    'Activo' => 'badge-status-success',
                                    'Pagado' => 'badge-status-info',
                                    'Vencido' => 'badge-status-danger',
                                    default => 'badge-status-muted',
                                };
                            @endphp

                            <span class="badge-status {{ $claseEstado }}">
                                {{ $credito->estado }}
                            </span>

                        </td>

                        <td class="text-end">

                            <a
                                href="{{ route('creditos.show', $credito) }}"
                                class="btn btn-sm btn-outline-primary"
                            >
                                Ver detalle
                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="7">

                            <div class="empty-state">

                                <div class="empty-state-icon">
                                    $
                                </div>

                                <h3 class="h6">
                                    No hay créditos registrados
                                </h3>

                                <p>
                                    Todavía no existen créditos que mostrar.
                                </p>

                                <a
                                    href="{{ route('creditos.create') }}"
                                    class="btn btn-primary btn-sm"
                                >
                                    + Registrar crédito
                                </a>

                            </div>

                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

        @if ($creditos->hasPages())
            <div class="card-footer bg-transparent border-0 px-4 py-3">
                {{ $creditos->links() }}
            </div>
        @endif

    </div>

@endsection