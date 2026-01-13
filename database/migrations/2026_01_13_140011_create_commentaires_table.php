<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commentaires', function (Blueprint $table) {
            $table->id();
            $table->text('contenu');
            $table->foreignId('utilisateur_id')->constrained('utilisateurs')->onDelete('cascade');
            $table->foreignId('ressource_id')->constrained('ressources')->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('commentaires')->onDelete('cascade');
            $table->boolean('est_approuve')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commentaires');
    }
};
