@extends('layouts.app')

@section('titulo', 'Clientes')

@section('content')

    <div class="page-header">
        <div>
            <span class="section-kicker">
                Administración
            </span>

            <h1 class="page-title">
                Clientes
            </h1>

            <p class="page-subtitle">
                Gestiona la información, estado y créditos de tus clientes.
            </p>
        </div>

        <div>
            <a href="{{ route('clientes.create') }}" class="btn btn-primary">
                + Nuevo cliente
            </a>
        </div>
    </div>

    <div class="card app-card mb-4">
        <div class="card-body p-4">

            <div class="filter-title mb-3">
                Buscar clientes
            </div>

            <form method="GET" class="row g-3 align-items-end">

                <div class="col-lg-6">
                    <label for="buscar" class="form-label">
                        Buscar
                    </label>

                    <input
                        type="text"
                        id="buscar"
                        name="buscar"
                        value="{{ request('buscar') }}"
                        class="form-control"
                        placeholder="Nombre, apellido o documento"
                    >
                </div>

                <div class="col-lg-3">
                    <label for="estado" class="form-label">
                        Estado
                    </label>

                    <select id="estado" name="estado" class="form-select">
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
                        <button type="submit" class="btn btn-primary">
                            Buscar
                        </button>

                        @if (request('buscar') || request('estado'))
                            <a
                                href="{{ route('clientes.index') }}"
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
                        Lista de clientes
                    </h2>

                    <p class="text-muted small mb-0">
                        Clientes registrados en el sistema.
                    </p>
                </div>

                <span class="badge-status badge-status-info">
                    {{ $clientes->total() }} registrados
                </span>

            </div>
        </div>

        <div class="table-responsive">

            <table class="table app-table table-hover align-middle mb-0">

                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Documento</th>
                        <th>Teléfono</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>

                <tbody>

                @forelse ($clientes as $cliente)

                    <tr>

                        <td>
                            <div class="fw-semibold">
                                {{ $cliente->nombres }} {{ $cliente->apellidos }}
                            </div>

                            <div class="small text-muted">
                                Cliente #{{ $cliente->id }}
                            </div>
                        </td>

                        <td>
                            {{ $cliente->documento_identidad }}
                        </td>

                        <td>
                            {{ $cliente->telefono ?? '—' }}
                        </td>

                        <td>
                            <span class="badge-status {{ $cliente->estado === 'Activo' ? 'badge-status-success' : 'badge-status-muted' }}">
                                {{ $cliente->estado }}
                            </span>
                        </td>

                        <td class="text-end">

                            <div class="d-inline-flex gap-1 flex-wrap justify-content-end">

                                <a
                                    href="{{ route('clientes.show', $cliente) }}"
                                    class="btn btn-sm btn-outline-secondary"
                                >
                                    Ver
                                </a>

                                <a
                                    href="{{ route('clientes.edit', $cliente) }}"
                                    class="btn btn-sm btn-outline-primary"
                                >
                                    Editar
                                </a>

                                <form
                                    action="{{ route('clientes.desactivar', $cliente) }}"
                                    method="POST"
                                    class="d-inline"
                                >
                                    @csrf
                                    @method('PATCH')

                                    <button
                                        type="submit"
                                        class="btn btn-sm btn-outline-{{ $cliente->estado === 'Activo' ? 'danger' : 'success' }}"
                                    >
                                        {{ $cliente->estado === 'Activo' ? 'Desactivar' : 'Activar' }}
                                    </button>
                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    👥
                                </div>

                                <h3 class="h6">
                                    No hay clientes registrados
                                </h3>

                                <p>
                                    Todavía no existen clientes que mostrar.
                                </p>

                                <a
                                    href="{{ route('clientes.create') }}"
                                    class="btn btn-primary btn-sm"
                                >
                                    + Registrar cliente
                                </a>
                            </div>
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

        @if ($clientes->hasPages())
            <div class="card-footer bg-transparent border-0 px-4 py-3">
                {{ $clientes->links() }}
            </div>
        @endif

    </div>

@endsection