<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'nom' => 'Administrateur',
                'description' => 'Administrateur de la plateforme avec tous les droits',
                'permissions' => json_encode([
                    'gerer_utilisateurs',
                    'gerer_ressources',
                    'gerer_activites',
                    'gerer_commentaires',
                    'moderer_contenu',
                    'voir_statistiques'
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Modérateur',
                'description' => 'Modérateur de contenu',
                'permissions' => json_encode([
                    'moderer_contenu',
                    'gerer_commentaires',
                    'voir_statistiques'
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Membre',
                'description' => 'Membre standard de la plateforme',
                'permissions' => json_encode([
                    'creer_ressources',
                    'creer_activites',
                    'commenter',
                    'participer_activites'
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Visiteur',
                'description' => 'Visiteur non inscrit',
                'permissions' => json_encode([
                    'voir_ressources_publiques'
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('roles')->insert($roles);
    }
}
