@extends('layouts.app')

@section('titulo', 'Empleados')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <span class="section-kicker">Gestión de empleados</span>
            <h1 class="page-title">Empleados</h1>
            <p class="page-subtitle mb-0">
                Administra los usuarios que tienen el rol de empleado.
            </p>
        </div>

        <a href="{{ route('empleados.create') }}" class="btn btn-success">
            + Nuevo empleado
        </a>
    </div>

    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-5">
            <label for="buscar" class="visually-hidden">Buscar</label>
            <input
                id="buscar"
                type="text"
                name="buscar"
                value="{{ request('buscar') }}"
                class="form-control"
                placeholder="Buscar por nombre de usuario"
            >
        </div>

        <div class="col-md-3">
            <label for="estado" class="visually-hidden">Estado</label>
            <select id="estado" name="estado" class="form-select">
                <option value="">Todos los estados</option>
                <option value="Activo" @selected(request('estado') === 'Activo')>
                    Activo
                </option>
                <option value="Inactivo" @selected(request('estado') === 'Inactivo')>
                    Inactivo
                </option>
            </select>
        </div>

        <div class="col-auto">
            <button type="submit" class="btn btn-outline-primary">
                Buscar
            </button>
        </div>

        @if (request()->filled('buscar') || request()->filled('estado'))
            <div class="col-auto">
                <a href="{{ route('empleados.index') }}" class="btn btn-outline-secondary">
                    Limpiar
                </a>
            </div>
        @endif
    </form>

    <div class="card app-card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
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
                                {{ $empleado->username }}
                            </td>

                            <td>
                                {{ $empleado->role->nombre }}
                            </td>

                            <td>
                                <span class="badge {{ $empleado->estado === 'Activo' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $empleado->estado }}
                                </span>
                            </td>

                            <td class="text-end">
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
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                No hay empleados registrados todavía.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $empleados->links() }}
    </div>
@endsection