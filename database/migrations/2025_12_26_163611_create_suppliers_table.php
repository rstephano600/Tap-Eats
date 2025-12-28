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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('business_name', 255);
            $table->string('slug', 255)->unique();
            $table->foreignId('business_type_id')->nullable()->constrained()->onDelete('cascade');
            $table->text('description')->nullable();
            $table->string('logo', 500)->nullable();
            $table->string('logo_url', 500)->nullable();
            $table->string('cover_image', 500)->nullable();
            $table->json('gallery_images')->nullable(); // Array of image URLs
            
            // Business verification
            $table->string('license_number', 100)->nullable();
            $table->string('tax_id', 100)->nullable();
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->timestamp('verified_at')->nullable();
            
            // Contact information
            $table->string('contact_email', 255);
            $table->string('contact_phone', 20);
            $table->string('website', 255)->nullable();
            
            // Operating details
            $table->json('operating_hours')->nullable(); // {monday: {open: "09:00", close: "22:00"}, ...}
            $table->integer('preparation_time')->default(30); // Average time in minutes
            $table->decimal('delivery_radius', 5, 2)->default(10.00); // in kilometers
            $table->decimal('min_order_amount', 10, 2)->default(0.00);
            $table->decimal('delivery_fee', 10, 2)->default(0.00);
            $table->decimal('free_delivery_above', 10, 2)->nullable();
            
            // Performance metrics
            $table->decimal('average_rating', 3, 2)->default(0.00);
            $table->integer('total_reviews')->default(0);
            $table->integer('total_orders')->default(0);
            $table->decimal('acceptance_rate', 5, 2)->default(100.00);
            $table->decimal('cancellation_rate', 5, 2)->default(0.00);
            
            // Status flags
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_open_now')->default(false); // Real-time status
            $table->boolean('accepts_orders')->default(true);
            $table->enum('status', ['active', 'inactive', 'locked', 'deleted'])->default('active');
            
            $table->timestamps();
            $table->softDeletes();

            $table->index('slug');
            $table->index(['is_active', 'is_featured']);
            $table->index('verification_status');
            $table->index('average_rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
