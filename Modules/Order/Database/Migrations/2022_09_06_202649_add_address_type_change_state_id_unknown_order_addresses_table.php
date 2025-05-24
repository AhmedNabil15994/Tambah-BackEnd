<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAddressTypeChangeStateIdUnknownOrderAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('unknown_order_address', function (Blueprint $table) {
            $table->string('address_type')->default('local');
            $table->json('json_data')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('unknown_order_address', function (Blueprint $table) {
            $table->dropColumn(['address_type','json_data']);
        });
    }
}