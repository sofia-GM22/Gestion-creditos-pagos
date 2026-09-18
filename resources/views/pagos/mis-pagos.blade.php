@extends('layouts.app')

@section('titulo', 'Mis Pagos')

@section('content')
    <div class="page-header">
        <div>
            <span class="section-kicker">Área del cliente</span>
            <h1 class="page-title">Mis pagos</h1>
            <p class="page-subtitle">
                Consulta tus pagos realizados, saldos posteriores y comprobantes.
            </p>
        </div>

        <a href="{{ route('creditos.mios') }}" class="btn btn-outline-primary">
            Ver mis créditos
        </a>
    </div>

    @if ($pagos->isEmpty())
        <div class="empty-state card">
            <div class="card-body text-center py-5">
                <div class="empty-state-icon">₿</div>
                <h3>Aún no tienes pagos registrados</h3>
                <p class="text-muted mb-0">
                    Cuando realices un pago, aparecerá aquí junto con su comprobante.
                </p>
            </div>
        </div>
    @else
        <div class="card app-card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Crédito</th>
                                <th>Monto</th>
                                <th>Referencia</th>
                                <th>Observaciones</th>
                                <th>Saldo posterior</th>
                                <th>Estado</th>
                                <th class="text-end">Comprobante</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($pagos as $pago)
                                @php
                                    $saldoPosterior = $pago->saldo_posterior
                                        ?? $pago->credito->saldo;

                                    $estadoPosterior = $pago->estado_posterior
                                        ?? $pago->credito->estado;
                                @endphp

                                <tr>
                                    <td>
                                        {{ $pago->fecha_pago->format('d/m/Y') }}
                                    </td>

                                    <td>
                                        <a
                                            href="{{ route('creditos.show', $pago->credito) }}"
                                            class="fw-semibold text-decoration-none"
                                        >
                                            #{{ $pago->credito->id }}
                                        </a>
                                    </td>

                                    <td class="fw-semibold">
                                        ${{ number_format($pago->monto, 2) }}
                                    </td>

                                    <td>
                                        {{ $pago->referencia ?: '—' }}
                                    </td>

                                    <td>
                                        {{ $pago->observaciones ?: '—' }}
                                    </td>

                                    <td class="fw-semibold">
                                        ${{ number_format($saldoPosterior, 2) }}
                                    </td>

                                    <td>
                                        @if ($estadoPosterior === 'Pagado')
                                            <span class="badge-status badge-status-success">
                                                Pagado
                                            </span>
                                        @elseif ($estadoPosterior === 'Vencido')
                                            <span class="badge-status badge-status-danger">
                                                Vencido
                                            </span>
                                        @else
                                            <span class="badge-status badge-status-primary">
                                                Activo
                                            </span>
                                        @endif
                                    </td>

                                    <td class="text-end">
                                        <a
                                            href="{{ route('pagos.show', $pago) }}"
                                            class="btn btn-sm btn-outline-primary"
                                        >
                                            Ver
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endsection