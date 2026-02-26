<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {

            if (!Schema::hasColumn('orders', 'preparing_at')) {
                $table->timestamp('preparing_at')->nullable()->after('confirmed_at');
            }

            if (!Schema::hasColumn('orders', 'ready_at')) {
                $table->timestamp('ready_at')->nullable()->after('preparing_at');
            }

            if (!Schema::hasColumn('orders', 'accepted_at')) {
                $table->timestamp('accepted_at')->nullable()->after('ready_at');
            }

            if (!Schema::hasColumn('orders', 'prepared_at')) {
                $table->timestamp('prepared_at')->nullable()->after('accepted_at');
            }

            if (!Schema::hasColumn('orders', 'dispatched_at')) {
                $table->timestamp('dispatched_at')->nullable()->after('prepared_at');
            }

            if (!Schema::hasColumn('orders', 'delivered_at')) {
                $table->timestamp('delivered_at')->nullable()->after('dispatched_at');
            }

            if (!Schema::hasColumn('orders', 'cancelled_at')) {
                $table->timestamp('cancelled_at')->nullable()->after('delivered_at');
            }

            if (!Schema::hasColumn('orders', 'estimated_delivery_time')) {
                $table->integer('estimated_delivery_time')->nullable()->after('cancelled_at');
            }

            if (!Schema::hasColumn('orders', 'subtotal')) {
                $table->decimal('subtotal', 12, 2)->default(0)->after('confirmed_at');
            }

            if (!Schema::hasColumn('orders', 'delivery_fee')) {
                $table->decimal('delivery_fee', 12, 2)->default(0)->after('subtotal');
            }

            if (!Schema::hasColumn('orders', 'service_fee')) {
                $table->decimal('service_fee', 12, 2)->default(0)->after('delivery_fee');
            }

            if (!Schema::hasColumn('orders', 'tax_amount')) {
                $table->decimal('tax_amount', 12, 2)->default(0)->after('service_fee');
            }

            if (!Schema::hasColumn('orders', 'discount_amount')) {
                $table->decimal('discount_amount', 12, 2)->default(0)->after('tax_amount');
            }

            if (!Schema::hasColumn('orders', 'coupon_code')) {
                $table->string('coupon_code')->nullable()->after('discount_amount');
            }

            if (!Schema::hasColumn('orders', 'total_amount')) {
                $table->decimal('total_amount', 12, 2)->default(0)->after('coupon_code');
            }

            if (!Schema::hasColumn('orders', 'special_instructions')) {
                $table->text('special_instructions')->nullable()->after('total_amount');
            }

            if (!Schema::hasColumn('orders', 'cancellation_reason')) {
                $table->text('cancellation_reason')->nullable()->after('special_instructions');
            }

            if (!Schema::hasColumn('orders', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('cancellation_reason');
            }

            if (!Schema::hasColumn('orders', 'delivery_distance')) {
                $table->decimal('delivery_distance', 8, 2)->nullable()->after('rejection_reason');
            }

            if (!Schema::hasColumn('orders', 'delivery_time')) {
                $table->integer('delivery_time')->nullable()->after('delivery_distance');
            }

            if (!Schema::hasColumn('orders', 'delivery_costs')) {
                $table->decimal('delivery_costs', 12, 2)->nullable()->after('delivery_time');
            }

            if (!Schema::hasColumn('orders', 'delivery_otp')) {
                $table->string('delivery_otp')->nullable()->after('delivery_costs');
            }

            if (!Schema::hasColumn('orders', 'delivery_photo')) {
                $table->string('delivery_photo')->nullable()->after('delivery_otp');
            }

            if (!Schema::hasColumn('orders', 'delivery_notes')) {
                $table->text('delivery_notes')->nullable()->after('delivery_photo');
            }

        });
    }

    public function down()
    {
        // Optional: Drop columns if needed
    }
};
