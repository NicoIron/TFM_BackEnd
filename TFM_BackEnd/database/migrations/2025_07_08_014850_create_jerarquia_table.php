<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jerarquia_inicial', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('id_jerarquia', 50)->unique();
            $table->string('id_organizacion', 50);
            $table->string('cargo', 100);
            $table->foreign('id_organizacion')
                ->references('id_organizacion')->on('organizacion')
                ->onDelete('restrict');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jerarquia_inicial');
    }
};
