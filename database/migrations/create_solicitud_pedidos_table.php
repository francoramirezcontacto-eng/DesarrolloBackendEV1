<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('solicitud_pedidos', function (Blueprint $table): void {
            $table->id();
            $table->string('cliente_referencia', 120);
            $table->unsignedInteger('cantidad');
            $table->date('fecha_requerida');
            $table->string('estado', 20);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitud_pedidos');
    }
};
