<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('menu_item_id')->nullable()->constrained()->onDelete('set null');
            $table->string('item_name', 255); // Store name in case item is deleted
            $table->text('item_description')->nullable();
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->foreignId('variant_id')->nullable()->constrained('menu_item_variants')->onDelete('set null');
            $table->string('variant_name', 100)->nullable();
            $table->json('selected_addons')->nullable(); // [{id, name, price, quantity}]
            $table->decimal('addons_total', 10, 2)->default(0.00);
            $table->text('special_instructions')->nullable();
            $table->decimal('subtotal', 10, 2); // (unit_price + addons) * quantity
            $table->timestamps();

            $table->index('order_id');
            $table->index('menu_item_id');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
