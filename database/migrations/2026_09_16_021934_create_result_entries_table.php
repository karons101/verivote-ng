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
    Schema::create('result_entries', function (Blueprint $table) {
        $table->id();
        $table->foreignId('result_id')->constrained()->cascadeOnDelete();
        $table->foreignId('candidate_id')->constrained()->cascadeOnDelete();
        $table->unsignedInteger('vote_count');
        $table->timestamps();
    });
}
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('result_entries');
    }
};