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
    Schema::create('verification_checks', function (Blueprint $table) {
        $table->id();
        $table->foreignId('result_id')->constrained()->cascadeOnDelete();
        $table->string('rule_name');
        $table->boolean('passed');
        $table->text('details')->nullable();
        $table->timestamp('executed_at');
        $table->timestamps();
    });
}
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verification_checks');
    }
};