@extends('layouts.app')

@section('titulo', 'Clientes')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Clientes</h1>
        <a href="{{ route('clientes.create') }}" class="btn btn-success">+ Nuevo cliente</a>
    </div>

    <form method="GET" class="row g-2 mb-3">
        <div class="col-auto">
            <input type="text" name="buscar" value="{{ request('buscar') }}"
                   class="form-control" placeholder="Buscar por nombre, apellido o documento">
        </div>
        <div class="col-auto">
            <select name="estado" class="form-select">
                <option value="">Todos los estados</option>
                <option value="Activo" @selected(request('estado')=='Activo')>Activo</option>
                <option value="Inactivo" @selected(request('estado')=='Inactivo')>Inactivo</option>
            </select>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-outline-primary">Buscar</button>
        </div>
    </form>

    <div class="card shadow-sm">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Nombre</th>
                    <th>Documento</th>
                    <th>Teléfono</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($clientes as $cliente)
                <tr>
                    <td>{{ $cliente->nombres }} {{ $cliente->apellidos }}</td>
                    <td>{{ $cliente->documento_identidad }}</td>
                    <td>{{ $cliente->telefono ?? '—' }}</td>
                    <td>
                        <span class="badge {{ $cliente->estado === 'Activo' ? 'bg-success' : 'bg-secondary' }}">
                            {{ $cliente->estado }}
                        </span>
                    </td>
                    <td class="text-end">
                        <a href="{{ route('clientes.show', $cliente) }}" class="btn btn-sm btn-outline-secondary">Ver</a>
                        <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                        <form action="{{ route('clientes.desactivar', $cliente) }}" method="POST" class="d-inline">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-outline-{{ $cliente->estado === 'Activo' ? 'danger' : 'success' }}">
                                {{ $cliente->estado === 'Activo' ? 'Desactivar' : 'Activar' }}
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">No hay clientes registrados todavía.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $clientes->links() }}
    </div>
@endsection