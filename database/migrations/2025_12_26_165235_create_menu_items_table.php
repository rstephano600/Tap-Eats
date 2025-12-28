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
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->foreignId('menu_category_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name', 255);
            $table->string('slug', 255);
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('discounted_price', 10, 2)->nullable();
            $table->string('image_url', 500)->nullable();
            $table->json('gallery_images')->nullable();
            
            // Item properties
            $table->integer('preparation_time')->nullable(); // minutes
            $table->integer('serves')->nullable(); // number of people
            $table->string('portion_size', 50)->nullable(); // "Regular", "Large"
            $table->integer('calories')->nullable();
            
            // Dietary information
            $table->boolean('is_vegetarian')->default(false);
            $table->boolean('is_vegan')->default(false);
            $table->boolean('is_gluten_free')->default(false);
            $table->boolean('is_halal')->default(false);
            $table->boolean('is_spicy')->default(false);
            $table->json('allergens')->nullable(); // ['nuts', 'dairy', 'eggs']
            $table->json('ingredients')->nullable(); // List of ingredients
            
            // Availability
            $table->boolean('is_available')->default(true);
            $table->json('available_times')->nullable(); // Time slots when available
            $table->integer('stock_quantity')->nullable(); // For limited items
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_popular')->default(false);
            
            // Metrics
            $table->integer('view_count')->default(0);
            $table->integer('order_count')->default(0);
            $table->decimal('average_rating', 3, 2)->default(0.00);
            
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->enum('status', ['active', 'inactive', 'locked', 'deleted'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['supplier_id', 'is_available']);
            $table->index('slug');
            $table->index(['is_featured', 'is_popular']);
            $table->index('order_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
