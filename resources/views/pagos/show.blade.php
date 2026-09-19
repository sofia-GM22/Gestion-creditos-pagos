@extends('layouts.app')

@section('titulo', 'Comprobante de Pago #'.$pago->id)

@section('content')

    <div class="page-header no-print">
        <div>
            <span class="section-kicker">
                Control financiero
            </span>

            <h1 class="page-title">
                Comprobante de Pago
            </h1>

            <p class="page-subtitle">
                Detalle del pago registrado en el sistema.
            </p>
        </div>

        <div>
            <button
                type="button"
                onclick="window.print()"
                class="btn btn-primary"
            >
                Imprimir comprobante
            </button>
        </div>
    </div>

    <div class="receipt-wrapper">

        <div class="card app-card receipt-card mx-auto">

            <div class="card-body p-4 p-md-5">

                <div class="text-center mb-4">

                    <div class="empty-state-icon">
                        ✓
                    </div>

                    <h2 class="h4 mb-1">
                        Sistema de Gestión de Créditos
                    </h2>

                    <p class="text-muted mb-1">
                        Comprobante de pago
                    </p>

                    <span class="badge-status badge-status-info">
                        #{{ $pago->id }}
                    </span>

                </div>

                <div class="receipt-divider"></div>

                <div class="row g-4 mb-4">

                    <div class="col-md-6">
                        <span class="app-summary-label">
                            Cliente
                        </span>

                        <div class="fw-semibold">
                            {{ $pago->credito->cliente->nombres }}
                            {{ $pago->credito->cliente->apellidos }}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <span class="app-summary-label">
                            Documento
                        </span>

                        <div class="fw-semibold">
                            {{ $pago->credito->cliente->documento_identidad }}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <span class="app-summary-label">
                            Crédito
                        </span>

                        <div class="fw-semibold">
                            #{{ $pago->credito->id }}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <span class="app-summary-label">
                            Fecha de pago
                        </span>

                        <div class="fw-semibold">
                            {{ $pago->fecha_pago->format('d/m/Y') }}
                        </div>
                    </div>

                </div>

                <div class="payment-highlight mb-4">

                    <span>
                        Monto pagado
                    </span>

                    <strong>
                        ${{ number_format($pago->monto, 2) }}
                    </strong>

                </div>

                <div class="receipt-details">

                    <div class="receipt-row">
                        <span>Referencia</span>
                        <strong>{{ $pago->referencia ?? '—' }}</strong>
                    </div>

                    <div class="receipt-row">
                        <span>Saldo restante</span>
                        <strong>
                            ${{ number_format($pago->credito->saldo, 2) }}
                        </strong>
                    </div>

                    <div class="receipt-row">
                        <span>Estado del crédito</span>

                        @php
                            $claseEstado = match ($pago->credito->estado) {
                                'Activo' => 'badge-status-success',
                                'Pagado' => 'badge-status-info',
                                'Vencido' => 'badge-status-danger',
                                default => 'badge-status-muted',
                            };
                        @endphp

                        <span class="badge-status {{ $claseEstado }}">
                            {{ $pago->credito->estado }}
                        </span>
                    </div>

                    <div class="receipt-row receipt-row-column">
                        <span>Observaciones</span>

                        <div>
                            {{ $pago->observaciones ?? '—' }}
                        </div>
                    </div>

                </div>

                <div class="receipt-divider mt-4"></div>

                <p class="text-center text-muted small mb-0">
                    Este documento corresponde al pago registrado en el sistema.
                </p>

            </div>

        </div>

    </div>

    <div class="text-center mt-4 no-print">

        <a
            href="{{ route('creditos.show', $pago->credito) }}"
            class="btn btn-outline-secondary"
        >
            Volver al crédito
        </a>

    </div>

    <style>
        .receipt-wrapper {
            max-width: 760px;
            margin: 0 auto;
        }

        .receipt-card {
            overflow: hidden;
        }

        .receipt-divider {
            height: 1px;
            background: var(--app-border);
            margin: 1.25rem 0;
        }

        .payment-highlight {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1.25rem 1.5rem;
            border-radius: var(--app-radius);
            background: var(--app-primary-soft);
            border: 1px solid #dbeafe;
        }

        .payment-highlight span {
            color: #475569;
            font-weight: 650;
        }

        .payment-highlight strong {
            color: var(--app-primary);
            font-size: 1.5rem;
        }

        .receipt-details {
            display: flex;
            flex-direction: column;
        }

        .receipt-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
            padding: 0.9rem 0;
            border-bottom: 1px solid #edf1f5;
        }

        .receipt-row:last-child {
            border-bottom: 0;
        }

        .receipt-row > span:first-child {
            color: var(--app-muted);
            font-size: 0.88rem;
            font-weight: 600;
        }

        .receipt-row > strong,
        .receipt-row > div {
            color: var(--app-text);
            text-align: right;
        }

        .receipt-row-column {
            align-items: flex-start;
        }

        @media (max-width: 576px) {
            .payment-highlight {
                align-items: flex-start;
                flex-direction: column;
            }

            .receipt-row {
                align-items: flex-start;
                flex-direction: column;
                gap: 0.3rem;
            }

            .receipt-row > strong,
            .receipt-row > div {
                text-align: left;
            }
        }

        @media print {
            .no-print,
            nav,
            .alert {
                display: none !important;
            }

            body {
                background: #ffffff !important;
            }

            .app-main {
                padding: 0 !important;
            }

            .receipt-wrapper {
                max-width: 100%;
            }

            .receipt-card {
                border: 0 !important;
                box-shadow: none !important;
            }

            .payment-highlight {
                border: 1px solid #ddd !important;
                background: #f8f8f8 !important;
            }
        }
    </style>

@endsection