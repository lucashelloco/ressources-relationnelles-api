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
        Schema::table('commentaires', function (Blueprint $table) {
            // Renommer utilisateur_id en auteur_id pour cohérence
            $table->renameColumn('utilisateur_id', 'auteur_id');

            // Supprimer est_approuve et ajouter statut avec enum
            $table->dropColumn('est_approuve');
            $table->enum('statut', ['en_attente', 'approuve', 'rejete'])->default('en_attente')->after('contenu');

            // Ajouter les champs de modération
            $table->foreignId('moderateur_id')->nullable()->constrained('utilisateurs')->onDelete('set null')->after('statut');
            $table->dateTime('date_moderation')->nullable()->after('moderateur_id');
            $table->text('raison_rejet')->nullable()->after('date_moderation');

            // Ajouter soft deletes
            $table->softDeletes();

            // Ajouter les index
            $table->index(['ressource_id', 'statut']);
            $table->index('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commentaires', function (Blueprint $table) {
            // Supprimer les index
            $table->dropIndex(['ressource_id', 'statut']);
            $table->dropIndex(['parent_id']);

            // Supprimer soft deletes
            $table->dropSoftDeletes();

            // Supprimer les champs de modération
            $table->dropForeign(['moderateur_id']);
            $table->dropColumn(['moderateur_id', 'date_moderation', 'raison_rejet']);

            // Restaurer est_approuve
            $table->dropColumn('statut');
            $table->boolean('est_approuve')->default(false);

            // Restaurer utilisateur_id
            $table->renameColumn('auteur_id', 'utilisateur_id');
        });
    }
};
