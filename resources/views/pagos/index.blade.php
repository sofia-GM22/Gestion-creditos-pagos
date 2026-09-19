@extends('layouts.app')

@section('titulo', 'Pagos')

@section('content')

    <div class="page-header">
        <div>
            <span class="section-kicker">
                Control financiero
            </span>

            <h1 class="page-title">
                Historial de Pagos
            </h1>

            <p class="page-subtitle">
                Consulta los pagos realizados y sus comprobantes.
            </p>
        </div>
    </div>

    <div class="card app-card mb-4">
        <div class="card-body p-4">

            <div class="filter-title mb-3">
                Buscar pagos
            </div>

            <form method="GET" class="row g-3 align-items-end">

                <div class="col-lg-8">
                    <label for="buscar" class="form-label">
                        Cliente
                    </label>

                    <input
                        type="text"
                        id="buscar"
                        name="buscar"
                        value="{{ request('buscar') }}"
                        class="form-control"
                        placeholder="Buscar por nombre del cliente"
                    >
                </div>

                <div class="col-lg-4">
                    <div class="d-flex gap-2">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            Buscar
                        </button>

                        @if (request('buscar'))
                            <a
                                href="{{ route('pagos.index') }}"
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
                        Pagos registrados
                    </h2>

                    <p class="text-muted small mb-0">
                        Historial de pagos realizados en el sistema.
                    </p>
                </div>

                <span class="badge-status badge-status-info">
                    {{ $pagos->total() }} registrados
                </span>

            </div>

        </div>

        <div class="table-responsive">

            <table class="table app-table table-hover align-middle mb-0">

                <thead>
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

                        <td>
                            {{ $pago->fecha_pago->format('d/m/Y') }}
                        </td>

                        <td>
                            <div class="fw-semibold">
                                {{ $pago->credito->cliente->nombres }}
                                {{ $pago->credito->cliente->apellidos }}
                            </div>

                            <div class="small text-muted">
                                Cliente #{{ $pago->credito->cliente_id }}
                            </div>
                        </td>

                        <td>
                            <a
                                href="{{ route('creditos.show', $pago->credito) }}"
                                class="text-decoration-none"
                            >
                                #{{ $pago->credito_id }}
                            </a>
                        </td>

                        <td>
                            <span class="fw-semibold">
                                ${{ number_format($pago->monto, 2) }}
                            </span>
                        </td>

                        <td>
                            {{ $pago->referencia ?? '—' }}
                        </td>

                        <td class="text-end">

                            <a
                                href="{{ route('pagos.show', $pago) }}"
                                class="btn btn-sm btn-outline-primary"
                            >
                                Ver comprobante
                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="6">

                            <div class="empty-state">

                                <div class="empty-state-icon">
                                    ✓
                                </div>

                                <h3 class="h6">
                                    No hay pagos registrados
                                </h3>

                                <p>
                                    Todavía no existen pagos que mostrar.
                                </p>

                            </div>

                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

        @if ($pagos->hasPages())
            <div class="card-footer bg-transparent border-0 px-4 py-3">
                {{ $pagos->links() }}
            </div>
        @endif

    </div>

@endsection