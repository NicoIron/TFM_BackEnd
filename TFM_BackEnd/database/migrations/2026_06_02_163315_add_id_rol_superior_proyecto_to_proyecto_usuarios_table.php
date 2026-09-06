<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proyecto_usuarios', function (Blueprint $table) {
            $table->unsignedBigInteger('id_rol_superior_proyecto')->nullable()->after('id_organizacion');
            $table->foreign('id_rol_superior_proyecto')
                ->references('id')->on('roles')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('proyecto_usuarios', function (Blueprint $table) {
            $table->dropForeign(['id_rol_superior_proyecto']);
            $table->dropColumn('id_rol_superior_proyecto');
        });
    }
};
