@extends('layouts.app')

@section('titulo', 'Inicio')

@section('content')
    <div class="p-5 mb-4 bg-white rounded-3 shadow-sm">
        <h1 class="display-6 fw-bold">Sistema de Gestión de Créditos</h1>
        <p class="col-md-8 fs-5 text-muted">
            Administra clientes, créditos y pagos desde un solo lugar.
        </p>
    </div>

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
                        Consulta clientes según el estado de su crédito: activo, pagado o vencido.
                    </p>
                    <a href="{{ route('clientes.por-estado-credito') }}" class="btn btn-primary">Ver por estado</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Pagos</h5>
                    <p class="card-text text-muted">
                        Próximamente: registro y consulta de pagos realizados.
                    </p>
                    <button class="btn btn-secondary" disabled>Próximamente</button>
                </div>
            </div>
        </div>
    </div>
@endsection