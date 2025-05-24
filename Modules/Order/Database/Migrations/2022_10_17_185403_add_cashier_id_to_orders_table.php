<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCashierIdToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('cashier_id')->nullable();
            $table->boolean("from_cashier")->default(false);
            $table->string("payment_number")->nullable();
            $table->foreign('cashier_id')->references('id')
                ->on('users')
                ->onDelete("set null")
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(["cashier_id"]);
            $table->dropColumn(["cashier_id", "from_cashier", "payment_number"]);
        });
    }
}
