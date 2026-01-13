<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ressource_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ressource_id')->constrained('ressources')->onDelete('cascade');
            $table->foreignId('tag_id')->constrained('tags')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['ressource_id', 'tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ressource_tag');
    }
};
