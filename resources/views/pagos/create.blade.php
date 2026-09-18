@extends('layouts.app')

@section('titulo', 'Registrar Pago')

@section('content')
    <h1 class="h3 mb-4">Registrar Pago</h1>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4"><strong>Cliente:</strong> {{ $credito->cliente->nombres }} {{ $credito->cliente->apellidos }}</div>
                <div class="col-md-4"><strong>Total del crédito:</strong> ${{ number_format($credito->total_credito, 2) }}</div>
                <div class="col-md-4"><strong>Saldo pendiente:</strong> ${{ number_format($credito->saldo, 2) }}</div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('pagos.store', $credito) }}">
                @csrf

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Fecha del pago</label>
                        <input type="date" name="fecha_pago" value="{{ old('fecha_pago', now()->toDateString()) }}" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Monto</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" min="0.01" max="{{ $credito->saldo }}"
                                   name="monto" value="{{ old('monto') }}" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Referencia</label>
                        <input type="text" name="referencia" value="{{ old('referencia') }}" class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Observaciones</label>
                        <textarea name="observaciones" class="form-control" rows="2">{{ old('observaciones') }}</textarea>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success">Registrar pago</button>
                    <a href="{{ route('creditos.show', $credito) }}" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
@endsection
