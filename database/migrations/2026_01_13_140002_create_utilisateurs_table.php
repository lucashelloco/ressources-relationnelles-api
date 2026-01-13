<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('utilisateurs', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 100);
            $table->string('prenom', 100);
            $table->string('email', 255)->unique();
            $table->string('mot_de_passe', 255);
            $table->string('telephone', 20)->nullable();
            $table->text('adresse')->nullable();
            $table->string('code_postal', 10)->nullable();
            $table->string('ville', 100)->nullable();
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');
            $table->boolean('est_actif')->default(true);
            $table->boolean('cgu_accepte')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('utilisateurs');
    }
};
