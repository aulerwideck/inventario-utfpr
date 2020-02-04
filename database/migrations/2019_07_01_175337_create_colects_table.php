<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateColectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('state_id');
            $table->integer('local_id');
            $table->integer('responsible_id');
            $table->integer('inventory_id');
            $table->integer('patrimony_id');
            $table->integer('user_id');
            $table->integer('tombo');
            $table->integer('tombo_old');
            $table->string('description',500);
            $table->string('observation',500);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('colects');
    }
}
