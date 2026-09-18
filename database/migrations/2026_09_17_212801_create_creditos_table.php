<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('creditos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('cliente_id')
                ->constrained('clientes')
                ->restrictOnDelete();

            $table->date('fecha_otorgamiento');
            $table->decimal('monto', 12, 2);
            $table->decimal('tasa_interes', 8, 2);
            $table->unsignedInteger('plazo');
            $table->decimal('total_credito', 12, 2);
            $table->decimal('saldo', 12, 2);
            $table->date('fecha_vencimiento');
            $table->string('estado')->default('Activo');

            $table->timestamps();

            $table->index('estado');
            $table->index('fecha_vencimiento');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('creditos');
    }
};