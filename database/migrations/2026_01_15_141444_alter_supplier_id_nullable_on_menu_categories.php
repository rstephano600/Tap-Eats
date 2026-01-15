<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('menu_categories', function (Blueprint $table) {
    $table->dropForeign(['supplier_id']);

    $table->foreignId('supplier_id')
          ->nullable()
          ->change();

    $table->foreign('supplier_id')
          ->references('id')
          ->on('suppliers')
          ->nullOnDelete();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
