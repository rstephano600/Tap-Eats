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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 50)->unique(); // ORD-20240101-0001
            $table->foreignId('customer_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('guest_session_id')->nullable()->constrained('guest_sessions')->onDelete('set null');
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_type_id')->constrained()->onDelete('restrict');
            
            // Order type
            $table->enum('order_type', ['instant', 'scheduled', 'catering', 'subscription'])->default('instant');
            
            // Order status workflow
            $table->enum('order_status', [
                'pending',           // Order placed, awaiting supplier acceptance
                'accepted',          // Supplier accepted
                'preparing',         // Food being prepared
                'ready',             // Ready for pickup
                'dispatched',        // Out for delivery
                'delivered',         // Successfully delivered
                'cancelled',         // Cancelled by customer/supplier
                'rejected',          // Rejected by supplier
                'failed'             // Failed delivery
            ])->default('pending');
            
            // Payment
            $table->enum('payment_method', ['cash', 'card', 'mobile_money', 'wallet'])->default('cash');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->string('payment_reference', 100)->nullable();
            
            // Delivery address
            $table->foreignId('delivery_address_id')->nullable()->constrained('customer_addresses')->onDelete('set null');
            $table->string('delivery_address_text', 500); // Full address as text
            $table->decimal('delivery_latitude', 10, 8)->nullable();
            $table->decimal('delivery_longitude', 11, 8)->nullable();
            $table->string('delivery_phone', 20);
            $table->string('delivery_contact_name', 100)->nullable();
            
            // Timing
            $table->timestamp('scheduled_at')->nullable(); // For scheduled orders
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('prepared_at')->nullable();
            $table->timestamp('dispatched_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->integer('estimated_delivery_time')->nullable(); // minutes
            
            // Pricing
            $table->decimal('subtotal', 10, 2);
            $table->decimal('delivery_fee', 10, 2)->default(0.00);
            $table->decimal('service_fee', 10, 2)->default(0.00);
            $table->decimal('tax_amount', 10, 2)->default(0.00);
            $table->decimal('discount_amount', 10, 2)->default(0.00);
            $table->string('coupon_code', 50)->nullable();
            $table->decimal('total_amount', 10, 2);
            
            // Special instructions
            $table->text('special_instructions')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->text('rejection_reason')->nullable();
            
            // Delivery proof
            $table->string('delivery_distance')->nullable();
            $table->timestamp('delivery_time')->nullable();
            $table->string('delivery_costs', 6)->nullable();
            $table->string('delivery_otp', 6)->nullable();
            $table->string('delivery_photo', 500)->nullable();
            $table->text('delivery_notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('status', ['active', 'inactive', 'locked', 'deleted'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->index('order_number');
            $table->index(['customer_id', 'order_status']);
            $table->index(['supplier_id', 'order_status']);
            $table->index('guest_session_id');
            $table->index('order_type');
            $table->index('created_at');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
