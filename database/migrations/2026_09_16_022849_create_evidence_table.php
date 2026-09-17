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
    Schema::create('evidence', function (Blueprint $table) {
        $table->id();
        $table->foreignId('result_id')->constrained()->cascadeOnDelete();
        $table->string('file_type');
        $table->string('storage_path');
        $table->string('file_hash');
        $table->timestamp('captured_at')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evidence');
    }
};