<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id(); // id INT PRIMARY KEY AUTO_INCREMENT
            $table->unsignedBigInteger('id_organizacion');
            $table->unsignedBigInteger('id_rol');
            $table->string('id_usuario', 50)->unique();
            $table->string('nombre', 100);
            $table->string('apellido', 100);
            $table->string('proyecto', 100)->nullable();
            $table->string('id_empleado', 50)->nullable();
            $table->string('correo', 100)->unique();
            $table->string('contraseña');
            $table->boolean('eliminado')->default(false);
            $table->timestamps();

            // Claves foráneas
            $table->foreign('id_organizacion')->references('id')->on('organizacion');
            $table->foreign('id_rol')->references('id')->on('roles');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
