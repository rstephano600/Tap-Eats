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
        Schema::create('catering_proposals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('catering_request_id')->constrained()->onDelete('cascade');
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->string('proposal_number', 50)->unique()->nullable(); // 修复这里：->nullable()

            // Proposal details
            $table->json('menu_items')->nullable(); // Proposed menu with items
            $table->decimal('price_per_person', 10, 2)->nullable();
            $table->decimal('total_price', 12, 2)->nullable();
            $table->integer('number_of_people')->nullable();
            $table->date('event_date')->nullable();
            $table->string('venue')->nullable();
            
            // Additional services
            $table->json('additional_services')->nullable(); // e.g., decor, equipment, staff
            $table->decimal('additional_services_cost', 10, 2)->default(0);
            
            // Payment terms
            $table->decimal('deposit_required', 10, 2)->nullable();
            $table->string('payment_terms')->nullable();
            
            // Status
            $table->enum('proposal_status', ['draft', 'submitted', 'under_review', 'accepted', 'rejected', 'revised'])
                  ->default('draft');
            
            // Communication
            $table->text('special_instructions')->nullable();
            $table->text('caterer_notes')->nullable();
            
            // Timestamps
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('Status', ['Active', 'Inactive', 'Locked', 'Deleted'])->default('Active');
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('proposal_number');
            $table->index('status');
            $table->index(['catering_request_id', 'supplier_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catering_proposals');
    }
};
