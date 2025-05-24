<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColsToOrderProductAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_product_attributes', function (Blueprint $table) {
            $table->json("name")->nullable();
            $table->string("type")->nullable();
            $table->json("validation")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_product_attributes', function (Blueprint $table) {
            $table->dropColumn(["name","type","validation"]);
        });
    }
}
