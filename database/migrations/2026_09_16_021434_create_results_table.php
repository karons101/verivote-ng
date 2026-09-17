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
    Schema::create('results', function (Blueprint $table) {
        $table->id();
        $table->foreignId('election_id')->constrained()->cascadeOnDelete();
        $table->foreignId('polling_unit_id')->constrained()->cascadeOnDelete();

        $table->string('submission_channel')->default('observer');
        $table->unsignedInteger('accredited_voters');
        $table->unsignedInteger('ballots_issued');
        $table->unsignedInteger('unused_ballots');
        $table->unsignedInteger('spoiled_ballots');
        $table->unsignedInteger('rejected_votes');
        $table->unsignedInteger('total_valid_votes');

        $table->string('payload_hash')->nullable();
        $table->string('status')->default('submitted');

        $table->timestamps();
    });
}
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};