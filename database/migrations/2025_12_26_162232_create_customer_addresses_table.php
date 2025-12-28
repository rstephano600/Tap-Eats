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
        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('guest_session_id')->nullable()->constrained('guest_sessions')->onDelete('cascade');
            $table->enum('address_type', ['home', 'work', 'other'])->default('home');
            $table->string('label', 50)->nullable(); // "Home", "Office", etc
            $table->string('address_line1', 255);
            $table->string('address_line2', 255)->nullable();
            $table->string('city', 100);
            $table->string('state', 100)->nullable();
            $table->string('postal_code', 20);
            $table->string('country', 100)->default('Tanzania');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->string('landmark', 255)->nullable();
            $table->string('contact_phone', 20)->nullable();
            $table->string('delivery_instructions', 500)->nullable();
            $table->boolean('is_default')->default(false);
            $table->enum('status', ['active', 'inactive', 'locked', 'deleted'])->default('active');
            $table->timestamps();

            // Geospatial index for location-based queries
            $table->spatialIndex(['latitude', 'longitude']);
            $table->index('user_id');
            $table->index('guest_session_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_addresses');
    }
};
