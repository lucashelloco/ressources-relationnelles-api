<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->json('permissions')->nullable()->after('description');
        });

        // Update existing roles with permissions
        DB::table('roles')->where('nom', 'Super Administrateur')->update([
            'permissions' => json_encode(['gerer_utilisateurs', 'gerer_ressources', 'gerer_activites', 'gerer_commentaires', 'moderer_contenu', 'voir_statistiques'])
        ]);

        DB::table('roles')->where('nom', 'Administrateur')->update([
            'permissions' => json_encode(['gerer_utilisateurs', 'gerer_ressources', 'gerer_activites', 'gerer_commentaires', 'moderer_contenu', 'voir_statistiques'])
        ]);

        DB::table('roles')->where('nom', 'Citoyen Connecté')->update([
            'permissions' => json_encode(['creer_ressources', 'creer_activites', 'commenter', 'participer_activites'])
        ]);

        DB::table('roles')->where('nom', 'Visiteur')->update([
            'permissions' => json_encode(['voir_ressources_publiques'])
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('permissions');
        });
    }
};
