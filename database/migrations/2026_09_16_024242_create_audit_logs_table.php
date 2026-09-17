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
    Schema::create('audit_logs', function (Blueprint $table) {
        $table->id();
        $table->string('entity_name');
        $table->unsignedBigInteger('entity_id');
        $table->string('action');
        $table->unsignedBigInteger('actor_id')->nullable();
        $table->json('previous_state')->nullable();
        $table->json('new_state')->nullable();
        $table->string('prev_audit_hash')->nullable();
        $table->string('current_hash');
        $table->timestamps();
    });
}
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};