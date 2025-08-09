<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('id_ticket_log', 50)->unique();
            $table->string('id_ticket', 50);
            $table->string('id_usuario', 50);
            $table->string('estado_anterior', 50)->nullable();
            $table->string('estado_nuevo', 50);
            $table->timestamp('fecha_cambio')->nullable();
            $table->foreign('id_ticket')
                ->references('id_ticket')->on('tickets')
                ->onDelete('restrict');
            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios')->onDelete('restrict');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets_logs');
    }
};
