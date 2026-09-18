<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreditoRequest;
use App\Models\Cliente;
use App\Models\Credito;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CreditoController extends Controller
{
    public function index(Request $request)
    {
        Credito::actualizarVencidos();

        $creditos = Credito::with('cliente')
            ->when($request->filled('estado'), fn ($q) => $q->where('estado', $request->estado))
            ->when($request->filled('buscar'), function ($q) use ($request) {
                $texto = $request->buscar;
                $q->whereHas('cliente', function ($qc) use ($texto) {
                    $qc->where('nombres', 'like', "%{$texto}%")
                        ->orWhere('apellidos', 'like', "%{$texto}%")
                        ->orWhere('documento_identidad', 'like', "%{$texto}%");
                });
            })
            ->orderByDesc('fecha_otorgamiento')
            ->paginate(15)
            ->withQueryString();

        return view('creditos.index', compact('creditos'));
    }

    public function create()
    {
        $clientes = Cliente::where('estado', 'Activo')->orderBy('apellidos')->get();

        return view('creditos.create', compact('clientes'));
    }

    public function store(CreditoRequest $request)
    {
        $datos = $request->validated();

        $totalCredito = round($datos['monto'] + ($datos['monto'] * $datos['tasa_interes'] / 100), 2);

        $credito = Credito::create([
            'cliente_id' => $datos['cliente_id'],
            'fecha_otorgamiento' => $datos['fecha_otorgamiento'],
            'monto' => $datos['monto'],
            'tasa_interes' => $datos['tasa_interes'],
            'plazo' => $datos['plazo'],
            'total_credito' => $totalCredito,
            'saldo' => $totalCredito,
            'fecha_vencimiento' => Carbon::parse($datos['fecha_otorgamiento'])->addMonths((int) $datos['plazo']),
            'estado' => 'Activo',
        ]);

        return redirect()->route('creditos.show', $credito)
            ->with('exito', 'Crédito registrado correctamente.');
    }

    public function show(Credito $credito)
    {
        $this->autorizarAcceso($credito);

        Credito::actualizarVencidos();
        $credito->refresh();
        $credito->load(['cliente', 'pagos']);

        return view('creditos.show', compact('credito'));
    }

    public function misCreditos(Request $request)
    {
        $cliente = $request->user()->cliente;

        abort_unless($cliente, 403, 'Tu usuario no está vinculado a un registro de cliente.');

        Credito::actualizarVencidos();

        $creditos = $cliente->creditos()->orderByDesc('fecha_otorgamiento')->get();

        return view('creditos.mis-creditos', compact('creditos'));
    }

    private function autorizarAcceso(Credito $credito): void
    {
        $usuario = auth()->user();

        if ($usuario->esCliente()) {
            $cliente = $usuario->cliente;
            abort_unless($cliente && $credito->cliente_id === $cliente->id, 403, 'No tienes permiso para ver este crédito.');
        }
    }
}
