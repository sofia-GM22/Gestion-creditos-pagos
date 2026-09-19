@extends('layouts.app')

@section('titulo', 'Empleados')

@section('content')

    <div class="page-header">
        <div>
            <span class="section-kicker">
                Administración de usuarios
            </span>

            <h1 class="page-title">
                Empleados
            </h1>

            <p class="page-subtitle">
                Administra los usuarios que tienen el rol de empleado.
            </p>
        </div>

        <div>
            <a
                href="{{ route('empleados.create') }}"
                class="btn btn-primary"
            >
                + Nuevo empleado
            </a>
        </div>
    </div>

    <div class="card app-card mb-4">

        <div class="card-body p-4">

            <div class="filter-title mb-3">
                Buscar empleados
            </div>

            <form method="GET" class="row g-3 align-items-end">

                <div class="col-lg-6">

                    <label for="buscar" class="form-label">
                        Usuario
                    </label>

                    <input
                        id="buscar"
                        type="text"
                        name="buscar"
                        value="{{ request('buscar') }}"
                        class="form-control"
                        placeholder="Buscar por nombre de usuario"
                    >

                </div>

                <div class="col-lg-3">

                    <label for="estado" class="form-label">
                        Estado
                    </label>

                    <select
                        id="estado"
                        name="estado"
                        class="form-select"
                    >
                        <option value="">
                            Todos los estados
                        </option>

                        <option
                            value="Activo"
                            @selected(request('estado') === 'Activo')
                        >
                            Activo
                        </option>

                        <option
                            value="Inactivo"
                            @selected(request('estado') === 'Inactivo')
                        >
                            Inactivo
                        </option>
                    </select>

                </div>

                <div class="col-lg-3">

                    <div class="d-flex gap-2">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            Buscar
                        </button>

                        @if (request()->filled('buscar') || request()->filled('estado'))

                            <a
                                href="{{ route('empleados.index') }}"
                                class="btn btn-outline-secondary"
                            >
                                Limpiar
                            </a>

                        @endif

                    </div>

                </div>

            </form>

        </div>

    </div>

    <div class="card app-card">

        <div class="card-header bg-transparent border-0 px-4 pt-4 pb-2">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <h2 class="h5 mb-1">
                        Lista de empleados
                    </h2>

                    <p class="text-muted small mb-0">
                        Usuarios con permisos de empleado.
                    </p>
                </div>

                <span class="badge-status badge-status-info">
                    {{ $empleados->total() }} registrados
                </span>

            </div>

        </div>

        <div class="table-responsive">

            <table class="table app-table table-hover align-middle mb-0">

                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>

                <tbody>

                @forelse ($empleados as $empleado)

                    <tr>

                        <td>
                            <div class="fw-semibold">
                                {{ $empleado->username }}
                            </div>

                            <div class="small text-muted">
                                Usuario #{{ $empleado->id }}
                            </div>
                        </td>

                        <td>
                            <span class="badge-status badge-status-primary">
                                {{ $empleado->role->nombre }}
                            </span>
                        </td>

                        <td>

                            <span class="badge-status {{ $empleado->estado === 'Activo' ? 'badge-status-success' : 'badge-status-muted' }}">
                                {{ $empleado->estado }}
                            </span>

                        </td>

                        <td class="text-end">

                            <div class="d-inline-flex gap-1 flex-wrap justify-content-end">

                                <a
                                    href="{{ route('empleados.edit', $empleado) }}"
                                    class="btn btn-sm btn-outline-primary"
                                >
                                    Editar
                                </a>

                                <form
                                    action="{{ route('empleados.desactivar', $empleado) }}"
                                    method="POST"
                                    class="d-inline"
                                >
                                    @csrf
                                    @method('PATCH')

                                    <button
                                        type="submit"
                                        class="btn btn-sm btn-outline-{{ $empleado->estado === 'Activo' ? 'danger' : 'success' }}"
                                    >
                                        {{ $empleado->estado === 'Activo' ? 'Desactivar' : 'Activar' }}
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="4">

                            <div class="empty-state">

                                <div class="empty-state-icon">
                                    👤
                                </div>

                                <h3 class="h6">
                                    No hay empleados registrados
                                </h3>

                                <p>
                                    Todavía no existen empleados que mostrar.
                                </p>

                                <a
                                    href="{{ route('empleados.create') }}"
                                    class="btn btn-primary btn-sm"
                                >
                                    + Registrar empleado
                                </a>

                            </div>

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

        @if ($empleados->hasPages())

            <div class="card-footer bg-transparent border-0 px-4 py-3">
                {{ $empleados->links() }}
            </div>

        @endif

    </div>

@endsection