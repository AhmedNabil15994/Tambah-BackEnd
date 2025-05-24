<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatalogAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalog_attributes', function (Blueprint $table) {
            $table->uuid('id');

            $table->string("value")->nullable();
            $table->string("catalogable_type")->nullable();
            $table->unsignedBigInteger("catalogable_id")->nullable();

            $table->unsignedBigInteger("attribute_id")->nullable();
            $table->foreign('attribute_id')
                ->references('id')->on('attributes')
                ->onUpdated("cascade")
                ->onDelete('cascade');

            $table->string("attribute_type")->nullable();

            $table->unsignedBigInteger("option_id")->nullable();
            $table->foreign('option_id')
                ->references('id')->on('attribute_options')
                ->onUpdated("cascade")
                ->onDelete('cascade');

            $table->primary("id");
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
        Schema::dropIfExists('catalog_attributes');
    }
}
