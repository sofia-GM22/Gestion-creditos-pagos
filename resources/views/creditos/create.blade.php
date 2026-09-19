@extends('layouts.app')

@section('titulo', 'Nuevo Crédito')

@section('content')

    <h1 class="h3 mb-4">Nuevo Crédito</h1>

    <div class="card shadow-sm">
        <div class="card-body">

            <form method="POST" action="{{ route('creditos.store') }}">
                @csrf

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Cliente</label>

                        <select name="cliente_id" class="form-select">
                            <option value="">-- Seleccione un cliente --</option>

                            @foreach ($clientes as $cliente)
                                <option value="{{ $cliente->id }}"
                                    @selected(old('cliente_id') == $cliente->id)>
                                    {{ $cliente->nombres }}
                                    {{ $cliente->apellidos }}
                                    ({{ $cliente->documento_identidad }})
                                </option>
                            @endforeach
                        </select>

                        @if ($clientes->isEmpty())
                            <div class="form-text text-danger">
                                No hay clientes activos registrados todavía.
                            </div>
                        @endif
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Fecha de otorgamiento</label>

                        <input type="date"
                               name="fecha_otorgamiento"
                               value="{{ old('fecha_otorgamiento', now()->toDateString()) }}"
                               class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Monto</label>

                        <div class="input-group">
                            <span class="input-group-text">$</span>

                            <input type="number"
                                   step="0.01"
                                   min="200"
                                   name="monto"
                                   value="{{ old('monto') }}"
                                   class="form-control">
                        </div>

                        <div class="form-text">
                            El monto mínimo del crédito es de $200.00.
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">
                            Tasa de interés / recargo (%)
                        </label>

                        <input type="number"
                               step="0.01"
                               min="0"
                               name="tasa_interes"
                               value="{{ old('tasa_interes', 0) }}"
                               class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Plazo (meses)</label>

                        <input type="number"
                               min="1"
                               name="plazo"
                               value="{{ old('plazo') }}"
                               class="form-control">
                    </div>

                </div>

                <p class="text-muted small mt-3 mb-0">
                    El total del crédito se calcula automáticamente como
                    <strong>monto + interés</strong>,
                    y el saldo inicial queda igual al total.
                    La fecha de vencimiento se calcula sumando
                    el plazo (en meses) a la fecha de otorgamiento.
                </p>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success">
                        Guardar
                    </button>

                    <a href="{{ route('creditos.index') }}"
                       class="btn btn-outline-secondary">
                        Cancelar
                    </a>
                </div>

            </form>

        </div>
    </div>

@endsection