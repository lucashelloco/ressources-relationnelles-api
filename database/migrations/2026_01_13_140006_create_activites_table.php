<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activites', function (Blueprint $table) {
            $table->id();
            $table->string('titre', 255);
            $table->text('description')->nullable();
            $table->enum('type', ['atelier', 'conference', 'groupe_parole', 'webinaire', 'formation'])->default('atelier');
            $table->enum('statut', ['planifie', 'en_cours', 'termine', 'annule'])->default('planifie');
            $table->dateTime('date_debut');
            $table->dateTime('date_fin');
            $table->string('lieu', 255)->nullable();
            $table->boolean('en_ligne')->default(false);
            $table->string('lien_visio', 500)->nullable();
            $table->integer('nb_participants_max')->default(20);
            $table->integer('nb_participants_actuels')->default(0);
            $table->foreignId('organisateur_id')->constrained('utilisateurs')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activites');
    }
};
