<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_organizacion')->constrained('organizacion');
            $table->foreignId('id_jerarquia')->constrained('jerarquia');
            $table->string('nombre_rol', 100);
            $table->string('jefe_inmediato', 100)->nullable();
            $table->boolean('eliminado')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
