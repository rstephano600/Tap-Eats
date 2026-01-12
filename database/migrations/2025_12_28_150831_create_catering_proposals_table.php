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
        Schema::create('catering_proposals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('catering_request_id')->constrained()->onDelete('cascade');
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->string('proposal_number', 50)->unique();
            
            // Proposal details
            $table->json('menu_items')->nullable(); // Proposed menu with items
            $table->decimal('price_per_person', 10, 2)->nullable();
            $table->decimal('total_price', 10, 2);
            $table->decimal('setup_fee', 10, 2)->default(0.00);
            $table->decimal('service_fee', 10, 2)->default(0.00);
            $table->text('inclusions')->nullable(); // What's included
            $table->text('exclusions')->nullable(); // What's not included
            $table->text('terms_and_conditions')->nullable();
            
            // Additional services
            $table->boolean('includes_setup')->default(false);
            $table->boolean('includes_service_staff')->default(false);
            $table->boolean('includes_equipment')->default(false);
            $table->boolean('includes_decoration')->default(false);
            $table->integer('staff_count')->nullable();
            
            // Validity
            $table->date('valid_until');
            $table->text('notes')->nullable();
            
            // Status
            $table->enum('catering_proposals_status', ['submitted', 'viewed', 'accepted', 'rejected', 'expired'])->default('submitted');
            $table->timestamp('submitted_at');
            $table->timestamp('viewed_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('status', ['active', 'inactive', 'locked', 'deleted'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['catering_request_id', 'status']);
            $table->index('supplier_id');
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
