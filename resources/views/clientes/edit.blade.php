@extends('layouts.app')

@section('titulo', 'Editar Cliente')

@section('content')
    <div class="page-header">
        <div>
            <span class="section-kicker">Gestión de clientes</span>
            <h1 class="page-title">Editar cliente</h1>
            <p class="page-subtitle">
                Actualiza la información del cliente seleccionado.
            </p>
        </div>
    </div>

    <div class="card app-card">
        <div class="card-body p-4 p-lg-5">
            <form method="POST"
      action="{{ route('clientes.update', $cliente) }}"
      novalidate
      data-cliente-form>
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <div class="col-md-6">
                        <label for="nombres" class="form-label">Nombres</label>
                        <input
                            id="nombres"
                            type="text"
                            name="nombres"
                            value="{{ old('nombres', $cliente->nombres) }}"
                            class="form-control @error('nombres') is-invalid @enderror"
                            autocomplete="given-name"
                            required
                        >
                        @error('nombres')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="apellidos" class="form-label">Apellidos</label>
                        <input
                            id="apellidos"
                            type="text"
                            name="apellidos"
                            value="{{ old('apellidos', $cliente->apellidos) }}"
                            class="form-control @error('apellidos') is-invalid @enderror"
                            autocomplete="family-name"
                            required
                        >
                        @error('apellidos')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="documento_identidad" class="form-label">Documento de identidad (DUI)</label>
                        <input
                            id="documento_identidad"
                            type="text"
                            name="documento_identidad"
                            value="{{ old('documento_identidad', $cliente->documento_identidad) }}"
                            class="form-control @error('documento_identidad') is-invalid @enderror"
                            placeholder="12345678-9"
                            maxlength="10"
                            inputmode="numeric"
                            autocomplete="off"
                            data-dui
                            required
                        >
                        <div class="form-text">Ingresa los 9 dígitos; el guion se colocará automáticamente.</div>
                        @error('documento_identidad')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input
    id="telefono"
    type="tel"
    name="telefono"
    value="{{ old('telefono', $cliente->telefono) }}"
    class="form-control @error('telefono') is-invalid @enderror"
    inputmode="numeric"
    maxlength="8"
    minlength="8"
    autocomplete="tel"
    data-telefono-digits
>
                        <div class="form-text">Debe contener exactamente 8 dígitos.</div>
                        @error('telefono')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="correo" class="form-label">Correo</label>
                        <input
                            id="correo"
                            type="email"
                            name="correo"
                            value="{{ old('correo', $cliente->correo) }}"
                            class="form-control @error('correo') is-invalid @enderror"
                            autocomplete="email"
                        >
                        @error('correo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="direccion" class="form-label">Dirección</label>
                        <input
                            id="direccion"
                            type="text"
                            name="direccion"
                            value="{{ old('direccion', $cliente->direccion) }}"
                            class="form-control @error('direccion') is-invalid @enderror"
                            autocomplete="street-address"
                        >
                        @error('direccion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-actions mt-4 pt-3">
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
@endsection