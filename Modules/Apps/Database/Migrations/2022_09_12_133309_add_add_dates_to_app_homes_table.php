<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Apps\Entities\AppHome;

class AddAddDatesToAppHomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_homes', function (Blueprint $table) {
            $table->tinyInteger('add_dates')->default(0);
        });

        foreach(AppHome::all() as $appHome){
            if($appHome->start_date || $appHome->end_date){
                $appHome->add_dates = 1;
                $appHome->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_homes', function (Blueprint $table) {
            $table->dropColumn(["add_dates"]);
        });
    }
}
