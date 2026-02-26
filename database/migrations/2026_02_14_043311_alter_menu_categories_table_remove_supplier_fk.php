<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMenuCategoriesTableRemoveSupplierFk extends Migration
{
    public function up()
    {
        Schema::table('menu_categories', function (Blueprint $table) {

            // Drop foreign key
            $table->dropForeign(['supplier_id']);

            // Optional: keep column but remove constraint
            // OR drop column completely
            // $table->dropColumn('supplier_id');
        });
    }

    public function down()
    {
        Schema::table('menu_categories', function (Blueprint $table) {

            $table->foreign('supplier_id')
                  ->references('id')
                  ->on('suppliers')
                  ->onDelete('cascade');
        });
    }
}

