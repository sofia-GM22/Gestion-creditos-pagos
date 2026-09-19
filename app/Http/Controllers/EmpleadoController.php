<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmpleadoRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmpleadoController extends Controller
{
    public function index(Request $request)
    {
        $empleados = User::query()
            ->whereHas('role', function ($q) {
                $q->where('nombre', 'Empleado');
            })
            ->when($request->filled('buscar'), function ($q) use ($request) {
                $q->where('username', 'like', '%' . $request->buscar . '%');
            })
            ->when($request->filled('estado'), function ($q) use ($request) {
                $q->where('estado', $request->estado);
            })
            ->orderBy('username')
            ->paginate(15)
            ->withQueryString();

        return view('empleados.index', compact('empleados'));
    }

    public function create()
    {
        return view('empleados.create');
    }

    public function store(EmpleadoRequest $request)
    {
        $rolEmpleado = Role::where('nombre', 'Empleado')->firstOrFail();

        User::create([
            'role_id' => $rolEmpleado->id,
            'username' => $request->validated('username'),
            'password' => Hash::make($request->validated('password')),
            'estado' => 'Activo',
        ]);

        return redirect()
            ->route('empleados.index')
            ->with('exito', 'Empleado registrado correctamente.');
    }

    public function edit(User $empleado)
    {
        $this->verificarQueSeaEmpleado($empleado);

        return view('empleados.edit', compact('empleado'));
    }

    public function update(EmpleadoRequest $request, User $empleado)
    {
        $this->verificarQueSeaEmpleado($empleado);

        $datos = $request->validated();

        $empleado->username = $datos['username'];
        $empleado->estado = $datos['estado'];

        if (! empty($datos['password'])) {
            $empleado->password = Hash::make($datos['password']);
        }

        $empleado->save();

        return redirect()
            ->route('empleados.index')
            ->with('exito', 'Empleado actualizado correctamente.');
    }

    public function desactivar(User $empleado)
    {
        $this->verificarQueSeaEmpleado($empleado);

        $empleado->update([
            'estado' => $empleado->estado === 'Activo'
                ? 'Inactivo'
                : 'Activo',
        ]);

        return redirect()
            ->route('empleados.index')
            ->with('exito', 'Estado del empleado actualizado.');
    }

    private function verificarQueSeaEmpleado(User $empleado): void
    {
        abort_unless(
            $empleado->role && $empleado->role->nombre === 'Empleado',
            404
        );
    }
}