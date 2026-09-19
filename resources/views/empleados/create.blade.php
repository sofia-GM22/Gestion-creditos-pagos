@extends('layouts.app')

@section('titulo', 'Nuevo Empleado')

@section('content')
    <div class="page-header">
        <div>
            <span class="section-kicker">Gestión de empleados</span>

            <h1 class="page-title">Nuevo empleado</h1>

            <p class="page-subtitle">
                Registra un nuevo usuario con el rol de empleado.
            </p>
        </div>
    </div>

    <div class="card app-card">
        <div class="card-body p-4 p-lg-5">

            <form
                method="POST"
                action="{{ route('empleados.store') }}"
            >
                @csrf

                <div class="row g-4">

                    <div class="col-md-6">
                        <label for="username" class="form-label">
                            Nombre de usuario
                        </label>

                        <input
                            id="username"
                            type="text"
                            name="username"
                            value="{{ old('username') }}"
                            class="form-control @error('username') is-invalid @enderror"
                            maxlength="50"
                            autocomplete="username"
                            required
                        >

                        @error('username')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="password" class="form-label">
                            Contraseña
                        </label>

                        <input
                            id="password"
                            type="password"
                            name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            minlength="8"
                            autocomplete="new-password"
                            required
                        >

                        <div class="form-text">
                            Debe tener al menos 8 caracteres.
                        </div>

                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                </div>

                <div class="alert alert-info mt-4 mb-0">
                    El usuario será registrado automáticamente con el rol
                    <strong>Empleado</strong> y estado <strong>Activo</strong>.
                </div>

                <div class="form-actions mt-4 pt-3">
                    <button type="submit" class="btn btn-success">
                        Guardar empleado
                    </button>

                    <a
                        href="{{ route('empleados.index') }}"
                        class="btn btn-outline-secondary"
                    >
                        Cancelar
                    </a>
                </div>
            </form>

        </div>
    </div>
@endsection