<?php

namespace App\Http\Controllers;

use App\Http\Requests\PagoRequest;
use App\Models\Credito;
use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PagoController extends Controller
{
    public function index(Request $request)
    {
        $pagos = Pago::with('credito.cliente')
            ->when($request->filled('buscar'), function ($q) use ($request) {
                $texto = $request->buscar;

                $q->whereHas('credito.cliente', function ($qc) use ($texto) {
                    $qc->where('nombres', 'like', "%{$texto}%")
                        ->orWhere('apellidos', 'like', "%{$texto}%");
                });
            })
            ->orderByDesc('fecha_pago')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('pagos.index', compact('pagos'));
    }

    public function create(Credito $credito)
    {
        $this->autorizarAccesoAlCredito($credito);

        abort_if(
            $credito->saldo <= 0,
            403,
            'Este crédito ya está pagado en su totalidad.'
        );

        return view('pagos.create', compact('credito'));
    }

    public function store(PagoRequest $request, Credito $credito)
    {
        $this->autorizarAccesoAlCredito($credito);

        $datos = $request->validated();
        $pago = null;

        DB::transaction(function () use ($datos, $credito, &$pago) {
            // Bloqueamos la fila del crédito para evitar condiciones de
            // carrera si dos pagos se registran al mismo tiempo.
            $creditoBloqueado = Credito::where('id', $credito->id)
                ->lockForUpdate()
                ->first();

            if ($datos['monto'] > $creditoBloqueado->saldo) {
                throw ValidationException::withMessages([
                    'monto' => 'El monto del pago no puede ser mayor al saldo pendiente ($'
                        . number_format($creditoBloqueado->saldo, 2) . ').',
                ]);
            }

            $pago = Pago::create([
                'credito_id' => $creditoBloqueado->id,
                'fecha_pago' => $datos['fecha_pago'],
                'monto' => $datos['monto'],
                'referencia' => $datos['referencia'] ?? null,
                'observaciones' => $datos['observaciones'] ?? null,
            ]);

            $nuevoSaldo = round(
                $creditoBloqueado->saldo - $datos['monto'],
                2
            );

            $creditoBloqueado->saldo = max($nuevoSaldo, 0);

            if ($creditoBloqueado->saldo <= 0) {
                $creditoBloqueado->estado = 'Pagado';
            }

            $creditoBloqueado->save();
        });

        return redirect()
            ->route('pagos.show', $pago)
            ->with('exito', 'Pago registrado correctamente.');
    }

    public function show(Pago $pago)
    {
        $pago->load('credito.cliente');

        $this->autorizarAcceso($pago);

        return view('pagos.show', compact('pago'));
    }

    public function misPagos(Request $request)
    {
        $cliente = $request->user()->cliente;

        abort_unless(
            $cliente,
            403,
            'Tu usuario no está vinculado a un registro de cliente.'
        );

        $pagos = Pago::with('credito.cliente')
            ->whereHas(
                'credito',
                fn ($q) => $q->where('cliente_id', $cliente->id)
            )
            ->orderByDesc('fecha_pago')
            ->orderByDesc('id')
            ->get();

        /*
         * Calculamos el saldo posterior real de cada pago.
         *
         * Ejemplo:
         * Crédito = $1,100
         * Pago 1  = $200  -> saldo posterior $900
         * Pago 2  = $900  -> saldo posterior $0
         *
         * La vista no debe utilizar el saldo ACTUAL del crédito para
         * todos los pagos históricos.
         */
        $pagos->groupBy('credito_id')->each(function ($pagosCredito) {
            $credito = $pagosCredito->first()->credito;

            $saldo = (float) $credito->total_credito;

            $pagosAscendente = $pagosCredito
                ->sortBy(function ($pago) {
                    return [
                        $pago->fecha_pago?->timestamp ?? 0,
                        $pago->id,
                    ];
                })
                ->values();

            foreach ($pagosAscendente as $pago) {
                $saldo = round($saldo - (float) $pago->monto, 2);
                $saldo = max($saldo, 0);

                $pago->saldo_posterior = $saldo;

                if ($saldo <= 0) {
                    $pago->estado_posterior = 'Pagado';
                } elseif ($credito->fecha_vencimiento->lt($pago->fecha_pago)) {
                    $pago->estado_posterior = 'Vencido';
                } else {
                    $pago->estado_posterior = 'Activo';
                }
            }
        });

        return view('pagos.mis-pagos', compact('pagos'));
    }

    private function autorizarAccesoAlCredito(Credito $credito): void
    {
        $usuario = auth()->user();

        if ($usuario->esCliente()) {
            $cliente = $usuario->cliente;

            abort_unless(
                $cliente && $credito->cliente_id === $cliente->id,
                403,
                'No tienes permiso para realizar pagos sobre este crédito.'
            );
        }
    }

    private function autorizarAcceso(Pago $pago): void
    {
        $usuario = auth()->user();

        if ($usuario->esCliente()) {
            $cliente = $usuario->cliente;

            abort_unless(
                $cliente &&
                $pago->credito->cliente_id === $cliente->id,
                403,
                'No tienes permiso para ver este comprobante.'
            );
        }
    }
}