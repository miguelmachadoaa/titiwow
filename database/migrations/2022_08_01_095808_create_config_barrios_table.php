<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfigBarriosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('config_barrios', function (Blueprint $table) {
            $table->id();
            $table->string('barrio_name');
            $table->integer('city_id');
            $table->integer('estado_registro')->default(1);
            $table->integer('id_user')->default(1);;
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
        Schema::dropIfExists('config_barrios');
    }
}
