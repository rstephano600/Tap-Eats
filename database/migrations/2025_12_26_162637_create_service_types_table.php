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
        Schema::create('service_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100); // "Instant Delivery", "Daily Meals", "Catering"
            $table->string('slug', 100)->unique();
            $table->text('description')->nullable();
            $table->string('icon', 255)->nullable();
            $table->string('image', 500)->nullable();
            $table->json('features')->nullable(); // List of features for this service
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('status', ['active', 'inactive', 'locked', 'deleted'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->index('slug');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_types');
    }
};
