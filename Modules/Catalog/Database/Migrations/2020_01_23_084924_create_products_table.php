<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->json('slug');
            $table->json('title');
            $table->json('usage')->nullable();
            $table->json('description')->nullable();
            $table->json('short_description')->nullable();
            $table->json('ingredients')->nullable();
            $table->json('seo_keywords')->nullable();
            $table->json('seo_description')->nullable();
            $table->string('image');
            $table->decimal('price',9,3);
            $table->string('sku')->nullable();
            $table->integer("sort")->default(0);
            $table->integer('qty')->nullable()->default(1);
            $table->boolean('status')->default(true);
            $table->boolean('all_countries')->default(true);
            $table->boolean('featured')->default(false);
            $table->boolean('is_new')->default(false);
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
        Schema::dropIfExists('products');
    }
}
