<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categorie_ressource', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categorie_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('ressource_id')->constrained('ressources')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['categorie_id', 'ressource_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categorie_ressource');
    }
};
