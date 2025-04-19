<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlpBancosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alp_bancos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_banco')->nullable();
            $table->string('descripcion_banco')->nullable();
            $table->string('codigo_banco')->nullable();
            $table->string('estado_registro')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alp_bancos');
    }
}
