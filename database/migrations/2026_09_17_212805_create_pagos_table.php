<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('credito_id')
                ->constrained('creditos')
                ->restrictOnDelete();

            $table->date('fecha_pago');
            $table->decimal('monto', 12, 2);
            $table->string('referencia', 100)->nullable();
            $table->string('observaciones', 255)->nullable();

            $table->timestamps();

            $table->index('fecha_pago');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};