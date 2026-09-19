@extends('layouts.app')

@section('titulo', 'Registrar Pago')

@section('content')

    <div class="page-header">
        <div>
            <span class="section-kicker">
                Operación financiera
            </span>

            <h1 class="page-title">
                Registrar Pago
            </h1>

            <p class="page-subtitle">
                Registra un nuevo pago y actualiza el saldo pendiente del crédito.
            </p>
        </div>
    </div>

    <div class="app-summary mb-4">
        <div class="card-body p-4">

            <div class="row g-4">

                <div class="col-md-4">
                    <span class="app-summary-label">
                        Cliente
                    </span>

                    <div class="app-summary-value">
                        {{ $credito->cliente->nombres }}
                        {{ $credito->cliente->apellidos }}
                    </div>
                </div>

                <div class="col-md-4">
                    <span class="app-summary-label">
                        Total del crédito
                    </span>

                    <div class="app-summary-value">
                        ${{ number_format($credito->total_credito, 2) }}
                    </div>
                </div>

                <div class="col-md-4">
                    <span class="app-summary-label">
                        Saldo pendiente
                    </span>

                    <div class="app-summary-value text-primary">
                        ${{ number_format($credito->saldo, 2) }}
                    </div>
                </div>

            </div>

        </div>
    </div>

    <div class="card app-card">

        <div class="card-header bg-transparent border-0 px-4 pt-4 pb-0">
            <h2 class="h5 mb-1">
                Datos del pago
            </h2>

            <p class="text-muted small mb-0">
                Completa la información del pago que deseas registrar.
            </p>
        </div>

        <div class="card-body p-4">

            <form method="POST" action="{{ route('pagos.store', $credito) }}">
                @csrf

                <div class="row g-4">

                    <div class="col-md-4">
                        <label for="fecha_pago" class="form-label">
                            Fecha del pago
                        </label>

                        <input
                            type="date"
                            id="fecha_pago"
                            name="fecha_pago"
                            value="{{ old('fecha_pago', now()->toDateString()) }}"
                            class="form-control @error('fecha_pago') is-invalid @enderror"
                        >

                        @error('fecha_pago')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="monto" class="form-label">
                            Monto
                        </label>

                        <div class="input-group">
                            <span class="input-group-text">
                                $
                            </span>

                            <input
                                type="number"
                                id="monto"
                                name="monto"
                                step="0.01"
                                min="0.01"
                                max="{{ $credito->saldo }}"
                                value="{{ old('monto') }}"
                                class="form-control @error('monto') is-invalid @enderror"
                                placeholder="0.00"
                            >
                        </div>

                        <div class="form-text">
                            El monto no puede superar el saldo pendiente de
                            ${{ number_format($credito->saldo, 2) }}.
                        </div>

                        @error('monto')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="referencia" class="form-label">
                            Referencia
                        </label>

                        <input
                            type="text"
                            id="referencia"
                            name="referencia"
                            value="{{ old('referencia') }}"
                            class="form-control @error('referencia') is-invalid @enderror"
                            placeholder="Ej. TRANS-001"
                        >

                        @error('referencia')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="observaciones" class="form-label">
                            Observaciones
                        </label>

                        <textarea
                            id="observaciones"
                            name="observaciones"
                            rows="4"
                            class="form-control @error('observaciones') is-invalid @enderror"
                            placeholder="Escribe alguna observación relacionada con el pago..."
                        >{{ old('observaciones') }}</textarea>

                        @error('observaciones')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                </div>

                <div class="form-actions mt-4 pt-3 border-top">

                    <button
                        type="submit"
                        class="btn btn-success"
                    >
                        Registrar pago
                    </button>

                    <a
                        href="{{ route('creditos.show', $credito) }}"
                        class="btn btn-outline-secondary"
                    >
                        Cancelar
                    </a>

                </div>

            </form>

        </div>

    </div>

@endsection