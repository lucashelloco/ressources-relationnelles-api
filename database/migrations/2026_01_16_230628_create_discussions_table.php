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
        Schema::create('discussions', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->text('description')->nullable();
            $table->foreignId('ressource_id')->constrained('ressources')->onDelete('cascade');
            $table->foreignId('createur_id')->constrained('utilisateurs')->onDelete('cascade');
            $table->boolean('est_actif')->default(true);
            $table->integer('nb_participants')->default(0);
            $table->timestamp('derniere_activite')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discussions');
    }
};
