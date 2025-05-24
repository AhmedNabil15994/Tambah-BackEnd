<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->json('slug')->nullable();
            $table->json('title')->nullable();
            /* $table->json('seo_keywords')->nullable();
            $table->json('seo_description')->nullable();
            $table->json('description')->nullable();
            $table->json('short_description')->nullable(); */
            $table->string('image')->nullable();
            $table->tinyInteger('status')->default(false);
            $table->boolean('show_in_home')->default(false);
            $table->softDeletes();
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
        Schema::dropIfExists('brands');
    }
}
