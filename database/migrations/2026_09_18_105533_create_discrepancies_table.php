<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Creates the discrepancies table for VeriVote NG.
 *
 * Stores structured integrity discrepancies detected during result
 * verification so they can be reviewed, reported, and displayed separately
 * from the underlying deterministic verification checks.
 */
return new class extends Migration
{
    /**
     * Run the migration.
     */
    public function up(): void
    {
        Schema::create('discrepancies', function (Blueprint $table) {
            $table->id();

            $table->foreignId('result_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('code');
            $table->string('type');
            $table->string('severity')->default('warning');
            $table->text('description');
            $table->string('status')->default('open');
            $table->timestamp('detected_at');

            $table->timestamps();

            $table->index(['result_id', 'status']);
            $table->index(['code', 'status']);
        });
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('discrepancies');
    }
};