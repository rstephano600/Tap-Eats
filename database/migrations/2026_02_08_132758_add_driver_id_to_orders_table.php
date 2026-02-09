<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('driver_id')->nullable()->after('supplier_id')->constrained('users')->onDelete('set null');
            
            // Add timestamp columns for order status tracking
            $table->timestamp('confirmed_at')->nullable()->after('scheduled_at');
            $table->timestamp('preparing_at')->nullable()->after('confirmed_at');
            $table->timestamp('ready_at')->nullable()->after('preparing_at');
            $table->timestamp('dispatched_at')->nullable()->after('ready_at');
            $table->timestamp('delivered_at')->nullable()->after('dispatched_at');
            $table->timestamp('completed_at')->nullable()->after('delivered_at');
            $table->timestamp('cancelled_at')->nullable()->after('completed_at');
            $table->text('cancellation_reason')->nullable()->after('cancelled_at');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['driver_id']);
            $table->dropColumn([
                'driver_id',
                'confirmed_at',
                'preparing_at',
                'ready_at',
                'dispatched_at',
                'delivered_at',
                'completed_at',
                'cancelled_at',
                'cancellation_reason'
            ]);
        });
    }
};