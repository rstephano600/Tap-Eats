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
        Schema::create('catering_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_number', 50)->unique()->nullable();
            $table->foreignId('customer_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('guest_session_id')->nullable()->constrained('guest_sessions')->onDelete('set null');
            
            // Event details
            $table->string('event_type', 100)->nullable(); // Wedding, Corporate, Birthday, etc
            $table->date('event_date')->nullable();
            $table->time('event_time')->nullable();
            $table->integer('duration_days')->nullable();
            $table->integer('duration_hours')->nullable();
            $table->integer('guest_count')->nullable();
            $table->string('venue_name', 255)->nullable();
            $table->string('venue_address', 500)->nullable();
            $table->decimal('venue_latitude', 10, 8)->nullable();
            $table->decimal('venue_longitude', 11, 8)->nullable();
            
            // Service requirements
            $table->enum('service_type', ['buffet', 'plated', 'cocktail', 'family_style'])->nullable();
            $table->json('cuisine_preferences')->nullable();
            $table->json('dietary_requirements')->nullable();
            $table->decimal('budget_min', 10, 2)->nullable();
            $table->decimal('budget_max', 10, 2)->nullable();
            $table->text('additional_requirements')->nullable();
            
            // Contact
            $table->string('contact_name', 100)->nullable();
            $table->string('contact_email', 255)->nullable();
            $table->string('contact_phone', 20)->nullable();
            
            // Status
            $table->enum('request_status', ['pending', 'quoted', 'accepted', 'rejected', 'completed', 'cancelled'])->default('pending');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('Status', ['Active', 'Inactive', 'Locked', 'Deleted'])->default('Active');
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catering_requests');
    }
};
