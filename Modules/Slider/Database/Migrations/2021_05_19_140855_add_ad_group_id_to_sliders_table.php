<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Slider\Entities\Slider;

class AddAdGroupIdToSlidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sliders', function (Blueprint $table) {
            $table->bigInteger('ad_group_id')->unsigned()->nullable()->after('id');
            $table->foreign('ad_group_id')->references('id')->on('advertising_groups')->onDelete('cascade');
            $table->tinyInteger('sort')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sliders', function (Blueprint $table) {
            $table->dropForeign('advertising_ad_group_id_foreign');
            $table->dropIndex('advertising_ad_group_id_foreign');
            $table->dropColumn(['ad_group_id','sort']);
        });
    }
}
