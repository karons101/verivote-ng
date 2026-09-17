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
        Schema::create('polling_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('election_id')->constrained()->cascadeOnDelete();
            $table->string('pu_code')->unique();
            $table->string('state');
            $table->string('lga');
            $table->string('ward');
            $table->string('name');
            $table->unsignedInteger('registered_voters');
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('polling_units');
    }
};