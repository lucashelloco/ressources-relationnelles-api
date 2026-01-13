<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ressources', function (Blueprint $table) {
            $table->id();
            $table->string('titre', 255);
            $table->text('description')->nullable();
            $table->longText('contenu')->nullable();
            $table->enum('type_ressource', ['article', 'video', 'podcast', 'infographie', 'guide', 'etude'])->default('article');
            $table->enum('type_relation', ['couple', 'famille', 'amitie', 'professionnel', 'soi'])->nullable();
            $table->enum('niveau', ['debutant', 'intermediaire', 'avance'])->default('debutant');
            $table->enum('statut', ['brouillon', 'publie', 'archive'])->default('brouillon');
            $table->enum('niveau_partage', ['public', 'prive', 'membres'])->default('public');
            $table->string('url_externe', 500)->nullable();
            $table->string('url_image', 500)->nullable();
            $table->integer('duree_lecture')->nullable();
            $table->foreignId('auteur_id')->constrained('utilisateurs')->onDelete('cascade');
            $table->date('date_creation');
            $table->date('date_publication')->nullable();
            $table->integer('nb_vues')->default(0);
            $table->integer('nb_favoris')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ressources');
    }
};
