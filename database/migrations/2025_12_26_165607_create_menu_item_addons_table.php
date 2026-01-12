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
        Schema::create('menu_item_addons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_item_id')->constrained()->onDelete('cascade');
            $table->string('addon_name', 100); // "Extra Cheese", "Bacon"
            $table->decimal('price', 10, 2);
            $table->boolean('is_available')->default(true);
            $table->integer('max_quantity')->default(1);
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('status', ['active', 'inactive', 'locked', 'deleted'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->index('menu_item_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_item_addons');
    }
};
