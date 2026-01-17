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
        Schema::create('musicians_bands', function (Blueprint $table) {
            $table->id();
            $table->foreignId('band_id')->constrained('bands');
            $table->foreignId('musician_id')->constrained('musicians');
            $table->foreignId('role_id')->constrained('roles');

            $table->unique(['band_id', 'musician_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('musicians_bands');
    }
};
