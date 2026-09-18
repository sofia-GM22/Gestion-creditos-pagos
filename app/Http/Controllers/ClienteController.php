<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClienteRequest;
use App\Models\Cliente;
use App\Models\Credito;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $clientes = Cliente::query()
            ->when($request->filled('buscar'), fn ($q) => $q->buscar($request->buscar))
            ->when($request->filled('estado'), fn ($q) => $q->where('estado', $request->estado))
            ->orderBy('apellidos')
            ->paginate(15)
            ->withQueryString();

        return view('clientes.index', compact('clientes'));
    }

    public function porEstadoCredito(Request $request)
    {
        Credito::actualizarVencidos();

        $estado = $request->get('estado_credito', 'Activo');

        $clientes = Cliente::whereHas('creditos', function ($q) use ($estado) {
            $q->where('estado', $estado);
        })->with(['creditos' => function ($q) use ($estado) {
            $q->where('estado', $estado);
        }])->paginate(15);

        return view('clientes.por-estado-credito', compact('clientes', 'estado'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(ClienteRequest $request)
    {
        Cliente::create($request->validated());

        return redirect()->route('clientes.index')
            ->with('exito', 'Cliente registrado correctamente.');
    }

    public function show(Cliente $cliente)
    {
        $cliente->load('creditos');
        return view('clientes.show', compact('cliente'));
    }

    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    public function update(ClienteRequest $request, Cliente $cliente)
    {
        $cliente->update($request->validated());

        return redirect()->route('clientes.index')
            ->with('exito', 'Cliente actualizado correctamente.');
    }

    public function desactivar(Cliente $cliente)
    {
        $cliente->update([
            'estado' => $cliente->estado === 'Activo' ? 'Inactivo' : 'Activo',
        ]);

        return redirect()->route('clientes.index')
            ->with('exito', 'Estado del cliente actualizado.');
    }
}