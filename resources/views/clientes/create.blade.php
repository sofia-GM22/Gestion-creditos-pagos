@extends('layouts.app')

@section('titulo', 'Nuevo Cliente')

@section('content')
    <h1 class="h3 mb-4">Nuevo Cliente</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('clientes.store') }}">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombres</label>
                        <input type="text" name="nombres" value="{{ old('nombres') }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Apellidos</label>
                        <input type="text" name="apellidos" value="{{ old('apellidos') }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Documento de identidad</label>
                        <input type="text" name="documento_identidad" value="{{ old('documento_identidad') }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono" value="{{ old('telefono') }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Correo</label>
                        <input type="email" name="correo" value="{{ old('correo') }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Dirección</label>
                        <input type="text" name="direccion" value="{{ old('direccion') }}" class="form-control">
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success">Guardar</button>
                    <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
@endsection