<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('order_items', function (Blueprint $table) {

            // Drop existing foreign key
            $table->dropForeign(['menu_item_id']);

            // Make column nullable
            $table->unsignedBigInteger('menu_item_id')->nullable()->change();

            // Re-add foreign key with SET NULL
            $table->foreign('menu_item_id')
                  ->references('id')
                  ->on('menu_items')
                  ->onDelete('set null')->after('order_id');
        });
    }

    public function down()
    {
        //
    }
};
