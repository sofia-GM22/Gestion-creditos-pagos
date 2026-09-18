@extends('layouts.app')

@section('titulo', 'Inicio')

@section('content')
    <div class="p-5 mb-4 bg-white rounded-3 shadow-sm">
        <h1 class="display-6 fw-bold">Sistema de Gestión de Créditos</h1>
        <p class="col-md-8 fs-5 text-muted">
            Administra clientes, créditos y pagos desde un solo lugar.
        </p>
    </div>

    @if (auth()->user()->esCliente())
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Mis Créditos</h5>
                        <p class="card-text text-muted">
                            Consulta el monto, plazo, saldo pendiente y estado de tus créditos.
                        </p>
                        <a href="{{ route('creditos.mios') }}" class="btn btn-primary">Ver mis créditos</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Mis Pagos</h5>
                        <p class="card-text text-muted">
                            Revisa el historial de pagos realizados y descarga tus comprobantes.
                        </p>
                        <a href="{{ route('pagos.mios') }}" class="btn btn-primary">Ver mis pagos</a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Clientes</h5>
                        <p class="card-text text-muted">
                            Registra, busca, edita y consulta el historial de crédito de cada cliente.
                        </p>
                        <a href="{{ route('clientes.index') }}" class="btn btn-primary">Ir a Clientes</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Créditos</h5>
                        <p class="card-text text-muted">
                            Registra nuevos créditos y consulta los activos, pagados y vencidos.
                        </p>
                        <a href="{{ route('creditos.index') }}" class="btn btn-primary">Ir a Créditos</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Pagos</h5>
                        <p class="card-text text-muted">
                            Registra pagos, valida el saldo y consulta el historial completo.
                        </p>
                        <a href="{{ route('pagos.index') }}" class="btn btn-primary">Ir a Pagos</a>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
