<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeStateKeyOrderAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_addresses', function (Blueprint $table) {

            DB::statement('SET FOREIGN_KEY_CHECKS = 0');
            foreach (Schema::getConnection()->getDoctrineSchemaManager()->listTableForeignKeys('delivery_charges') as $foreignKey) {

                $foreignKey->getName() == 'order_addresses_state_id_foreign' ? $table->dropForeign('delivery_charges_state_id_foreign') : null;
            }

            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
