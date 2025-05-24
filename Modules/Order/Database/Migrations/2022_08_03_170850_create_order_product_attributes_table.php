<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderProductAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_product_attributes', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('order_product_attributes_id')->unsigned();
            $table->string('order_product_attributes_type')->nullable();

            $table->unsignedBigInteger("attribute_id")->nullable();
            $table->foreign('attribute_id')
                ->references('id')->on('attributes')
                ->onUpdated("cascade")
                ->onDelete('set null');

            $table->string("value")->nullable();

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
        Schema::dropIfExists('order_product_attributes');
    }
}
