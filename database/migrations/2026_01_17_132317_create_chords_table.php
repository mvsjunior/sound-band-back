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
        Schema::create('chords', function (Blueprint $table) {
            $table->id();
            $table->enum(
                'tone',
                [
                    'C',
                    'C#',
                    'D',
                    'Eb',
                    'E',
                    'F',
                    'F#',
                    'G',
                    'Ab',
                    'A',
                    'Bb',
                    'B',
                    'Cm',
                    'C#m',
                    'Dm',
                    'Ebm',
                    'Em',
                    'Fm',
                    'F#m',
                    'Gm',
                    'Abm',
                    'Am',
                    'Bbm',
                    'Bm'
                ]
            );
            $table->foreignId('chord_content_id')->constrained('chord_contents')->cascadeOnDelete();
            $table->foreignId('music_id')->constrained('musics')->cascadeOnDelete();
            $table->string('version')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chords');
    }
};
