@extends('layouts.app')

@section('titulo', 'Editar Empleado')

@section('content')
    <div class="page-header">
        <div>
            <span class="section-kicker">Gestión de empleados</span>

            <h1 class="page-title">Editar empleado</h1>

            <p class="page-subtitle">
                Actualiza los datos y el estado del empleado.
            </p>
        </div>
    </div>

    <div class="card app-card">
        <div class="card-body p-4 p-lg-5">

            <form
                method="POST"
                action="{{ route('empleados.update', $empleado) }}"
            >
                @csrf
                @method('PUT')

                <div class="row g-4">

                    <div class="col-md-6">
                        <label for="username" class="form-label">
                            Nombre de usuario
                        </label>

                        <input
                            id="username"
                            type="text"
                            name="username"
                            value="{{ old('username', $empleado->username) }}"
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
                            Nueva contraseña
                        </label>

                        <input
                            id="password"
                            type="password"
                            name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            minlength="8"
                            autocomplete="new-password"
                        >

                        <div class="form-text">
                            Déjala vacía para conservar la contraseña actual.
                        </div>

                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="estado" class="form-label">
                            Estado
                        </label>

                        <select
                            id="estado"
                            name="estado"
                            class="form-select @error('estado') is-invalid @enderror"
                            required
                        >
                            <option
                                value="Activo"
                                @selected(old('estado', $empleado->estado) === 'Activo')
                            >
                                Activo
                            </option>

                            <option
                                value="Inactivo"
                                @selected(old('estado', $empleado->estado) === 'Inactivo')
                            >
                                Inactivo
                            </option>
                        </select>

                        @error('estado')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Rol
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="Empleado"
                            disabled
                        >

                        <div class="form-text">
                            El rol de empleado no puede cambiarse desde esta pantalla.
                        </div>
                    </div>

                </div>

                <div class="form-actions mt-4 pt-3">
                    <button type="submit" class="btn btn-primary">
                        Guardar cambios
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