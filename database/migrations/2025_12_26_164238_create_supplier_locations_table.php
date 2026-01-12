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
        Schema::create('supplier_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->string('location_name', 100)->nullable(); // "Main Branch", "Downtown"
            $table->string('address_line1', 255);
            $table->string('address_line2', 255)->nullable();
            $table->string('city', 100);
            $table->string('state', 100)->nullable();
            $table->string('postal_code', 20);
            $table->string('country', 100)->default('Tanzania');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->string('landmark', 255)->nullable();
            $table->string('phone', 20)->nullable();
            $table->boolean('is_primary')->default(false);
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('status', ['active', 'inactive', 'locked', 'deleted'])->default('active');
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_locations');
    }
};
