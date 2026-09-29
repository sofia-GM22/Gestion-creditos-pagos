<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClienteRequest;
use App\Models\Cliente;
use App\Models\Credito;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $clientes = Cliente::query()
            ->with('usuario')
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
        $datos = $request->validated();

        // Separamos los datos de acceso (tabla users) de los datos del cliente.
        $datosCliente = Arr::except($datos, ['username', 'password']);

        $rolCliente = Role::firstOrCreate(
            ['nombre' => 'Cliente'],
            ['descripcion' => 'Cliente del sistema']
        );

        // Transacción: o se crean el usuario y el cliente, o no se crea nada.
        DB::transaction(function () use ($datos, $datosCliente, $rolCliente) {
            $usuario = User::create([
                'role_id' => $rolCliente->id,
                'username' => $datos['username'],
                'password' => Hash::make($datos['password']),
                'estado' => 'Activo',
            ]);

            Cliente::create($datosCliente + ['usuario_id' => $usuario->id]);
        });

        return redirect()->route('clientes.index')
            ->with('exito', 'Cliente registrado correctamente. Ya puede iniciar sesión con su usuario y contraseña.');
    }

    public function show(Cliente $cliente)
    {
        $cliente->load(['creditos', 'usuario']);
        return view('clientes.show', compact('cliente'));
    }

    public function edit(Cliente $cliente)
    {
        $cliente->load('usuario');
        return view('clientes.edit', compact('cliente'));
    }

    public function update(ClienteRequest $request, Cliente $cliente)
    {
        $datos = $request->validated();
        $datosCliente = Arr::except($datos, ['username', 'password']);

        $username = $datos['username'] ?? null;
        $password = $datos['password'] ?? null;

        DB::transaction(function () use ($cliente, $datosCliente, $username, $password) {
            $cliente->update($datosCliente);

            $usuario = $cliente->usuario;

            if ($usuario) {
                // Seguridad: nunca tocar credenciales de un usuario que no sea Cliente
                // (por ejemplo, un administrador o empleado).
                if ($usuario->esCliente()) {
                    $usuario->username = $username;

                    // Contraseña vacía = se conserva la actual.
                    if (filled($password)) {
                        $usuario->password = Hash::make($password);
                    }

                    $usuario->save();
                }

                return;
            }

            // Cliente registrado antes de esta función: se le crea su acceso ahora.
            if (filled($username) && filled($password)) {
                $rolCliente = Role::firstOrCreate(
                    ['nombre' => 'Cliente'],
                    ['descripcion' => 'Cliente del sistema']
                );

                $nuevoUsuario = User::create([
                    'role_id' => $rolCliente->id,
                    'username' => $username,
                    'password' => Hash::make($password),
                    'estado' => $cliente->estado,
                ]);

                $cliente->update(['usuario_id' => $nuevoUsuario->id]);
            }
        });

        return redirect()->route('clientes.index')
            ->with('exito', 'Cliente actualizado correctamente.');
    }

    public function desactivar(Cliente $cliente)
    {
        $nuevoEstado = $cliente->estado === 'Activo' ? 'Inactivo' : 'Activo';

        DB::transaction(function () use ($cliente, $nuevoEstado) {
            $cliente->update(['estado' => $nuevoEstado]);

            // El estado del usuario se mantiene igual al del cliente:
            // un cliente inactivo no puede iniciar sesión.
            $usuario = $cliente->usuario;

            if ($usuario && $usuario->esCliente()) {
                $usuario->update(['estado' => $nuevoEstado]);
            }
        });

        return redirect()->route('clientes.index')
            ->with('exito', 'Estado del cliente actualizado.');
    }
}
