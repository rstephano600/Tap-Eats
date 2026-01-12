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
        Schema::create('guest_carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guest_session_id')->constrained('guest_sessions')->onDelete('cascade');
            $table->foreignId('menu_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->foreignId('variant_id')->nullable()->constrained('menu_item_variants')->onDelete('set null');
            $table->json('selected_addons')->nullable(); // [{id, name, price, quantity}]
            $table->text('special_instructions')->nullable();
            $table->decimal('item_total', 10, 2);
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('status', ['active', 'inactive', 'locked', 'deleted'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->index('guest_session_id');
            $table->index(['guest_session_id', 'supplier_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guest_carts');
    }
};
