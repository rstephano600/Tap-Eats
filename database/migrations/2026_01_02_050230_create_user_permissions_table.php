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
Schema::create('user_permissions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->foreignId('permission_id')->constrained()->cascadeOnDelete();

    // grant = user gets permission
    // revoke = user is blocked from permission even if role has it
    $table->enum('type', ['grant', 'revoke'])->default('grant');
    $table->enum('status', ['active', 'inactive', 'locked', 'deleted'])->default('active');
    $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
    $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
    $table->timestamps();

    $table->unique(['user_id', 'permission_id']);
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_permissions');
    }
};
