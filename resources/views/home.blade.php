@extends('layouts.app')

@section('titulo', 'Inicio')

@section('content')

    <div class="page-header">
        <div>
            <span class="section-kicker">
                Panel principal
            </span>

            <h1 class="page-title">
                Sistema de Gestión de Créditos
            </h1>

            <p class="page-subtitle">
                Administra clientes, créditos y pagos desde un solo lugar.
            </p>
        </div>
    </div>

    @if (auth()->user()->esCliente())

        <div class="row g-4">

            <div class="col-md-6">
                <div class="card app-card h-100">
                    <div class="card-body p-4">

                        <div class="empty-state-icon mx-0 mb-3">
                            $
                        </div>

                        <h2 class="card-title h5 mb-2">
                            Mis Créditos
                        </h2>

                        <p class="card-text text-muted mb-4">
                            Consulta el monto, plazo, saldo pendiente y estado
                            de tus créditos.
                        </p>

                        <a
                            href="{{ route('creditos.mios') }}"
                            class="btn btn-primary"
                        >
                            Ver mis créditos
                        </a>

                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card app-card h-100">
                    <div class="card-body p-4">

                        <div class="empty-state-icon mx-0 mb-3">
                            ✓
                        </div>

                        <h2 class="card-title h5 mb-2">
                            Mis Pagos
                        </h2>

                        <p class="card-text text-muted mb-4">
                            Revisa el historial de pagos realizados,
                            saldos posteriores y comprobantes.
                        </p>

                        <a
                            href="{{ route('pagos.mios') }}"
                            class="btn btn-primary"
                        >
                            Ver mis pagos
                        </a>

                    </div>
                </div>
            </div>

        </div>

    @else

        <div class="row g-4">

            <div class="col-md-4">
                <div class="card app-card h-100">
                    <div class="card-body p-4">

                        <div class="empty-state-icon mx-0 mb-3">
                            👥
                        </div>

                        <h2 class="card-title h5 mb-2">
                            Clientes
                        </h2>

                        <p class="card-text text-muted mb-4">
                            Registra, busca, edita y consulta la información
                            de los clientes y su historial de crédito.
                        </p>

                        <a
                            href="{{ route('clientes.index') }}"
                            class="btn btn-primary"
                        >
                            Ir a Clientes
                        </a>

                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card app-card h-100">
                    <div class="card-body p-4">

                        <div class="empty-state-icon mx-0 mb-3">
                            $
                        </div>

                        <h2 class="card-title h5 mb-2">
                            Créditos
                        </h2>

                        <p class="card-text text-muted mb-4">
                            Registra nuevos créditos y consulta los créditos
                            activos, pagados y vencidos.
                        </p>

                        <a
                            href="{{ route('creditos.index') }}"
                            class="btn btn-primary"
                        >
                            Ir a Créditos
                        </a>

                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card app-card h-100">
                    <div class="card-body p-4">

                        <div class="empty-state-icon mx-0 mb-3">
                            ✓
                        </div>

                        <h2 class="card-title h5 mb-2">
                            Pagos
                        </h2>

                        <p class="card-text text-muted mb-4">
                            Registra pagos, valida saldos y consulta
                            el historial completo.
                        </p>

                        <a
                            href="{{ route('pagos.index') }}"
                            class="btn btn-primary"
                        >
                            Ir a Pagos
                        </a>

                    </div>
                </div>
            </div>

        </div>

    @endif

@endsection