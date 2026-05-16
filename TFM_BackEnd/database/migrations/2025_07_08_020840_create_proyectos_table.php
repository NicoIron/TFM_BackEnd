<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proyectos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('id_proyecto', 50)->unique();
            $table->string('id_organizacion', 50);
            $table->string('nombre_proyecto', 100);
            $table->text('descripcion')->nullable();
            $table->foreign('id_organizacion')
                ->references('id_organizacion')->on('organizacion')
                ->onDelete('restrict');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proyectos');
    }
};
