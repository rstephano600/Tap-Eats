<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

// 1. Official Platform Contacts (Updatable/Multiple)
Schema::create('platform_contacts', function (Blueprint $table) {
    $table->id();
    $table->string('type'); // e.g., 'Email', 'Phone', 'WhatsApp', 'Address'
    $table->string('value'); // The actual contact info
    $table->string('label')->nullable(); // e.g., 'Customer Support', 'Sales'
    $table->string('icon')->nullable(); // Bootstrap icon class
    $table->boolean('is_active')->default(true);
    $table->timestamps();
    $table->softDeletes();
});

// 2. Customer Inquiries (General Contact Form)
Schema::create('contact_inquiries', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email');
    $table->string('subject');
    $table->text('message');
    $table->string('status')->default('pending'); // pending, responded, closed
            $table->timestamps();
            $table->softDeletes();
});

// 3. Supplier/Restaurant Reviews & Comments
Schema::create('supplier_reviews', function (Blueprint $table) {
    $table->id();
    $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
    $table->foreignId('user_id')->constrained()->onDelete('cascade'); // The customer
    $table->integer('rating')->default(5);
    $table->text('comment');
    $table->boolean('is_visible')->default(true);
            $table->timestamps();
            $table->softDeletes();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts_tables');
    }
};
