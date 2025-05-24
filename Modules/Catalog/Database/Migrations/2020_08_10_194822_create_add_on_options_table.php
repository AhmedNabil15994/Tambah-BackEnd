<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddOnOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('add_on_options', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->json('option');
            $table->bigInteger('add_on_id')->unsigned();
            $table->float('price', 8, 2)->default(0);
            $table->boolean('default')->nullable();
            $table->foreign('add_on_id')->references('id')->on('add_ons')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('add_on_options');
    }
}
