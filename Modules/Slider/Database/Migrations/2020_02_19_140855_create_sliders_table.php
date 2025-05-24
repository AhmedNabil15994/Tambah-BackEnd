<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSlidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sliders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->json('slug')->nullable();
            $table->json('short_title')->nullable();
            $table->json('title')->nullable();
            $table->json('short_description')->nullable();
            $table->json('description')->nullable();
            $table->string('image');
            $table->string('link')->default('#');
            $table->boolean('status')->default(false);
            $table->date('start_at');
            $table->date('end_at');
            $table->enum('type',Modules\Slider\Entities\Slider::TYPES)->default('slider');
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
        Schema::dropIfExists('sliders');
    }
}
