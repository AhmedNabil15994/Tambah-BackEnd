<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeProductVariantIdOrderVariantProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_variant_products', function (Blueprint $table) {

            DB::beginTransaction();
            $table->json('product_variant_title')->nullable()->after('id');

            DB::statement('SET FOREIGN_KEY_CHECKS = 0');

            $table->dropForeign(['product_variant_id']);

            $table->bigInteger('product_variant_id')->unsigned()->nullable()->change();
            
            $table->foreign('product_variant_id')->references('id')->on('product_variants')->onUpdate('cascade')->onDelete('set null');

            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
            DB::commit();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_variant_products', function (Blueprint $table) {
            $table->dropColumn(['product_variant_title']);
        });
    }
}
