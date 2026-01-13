<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Utilisateur;
use App\Models\Role;

class UtilisateurSeeder extends Seeder
{
    public function run(): void
    {
        $roles = Role::all()->keyBy('slug');

        $utilisateurs = [
            [
                'nom' => 'Martin',
                'prenom' => 'Sophie',
                'email' => 'sophie.martin@admin.com',
                'mot_de_passe' => bcrypt('SuperAdmin123!'),
                'role_id' => $roles['super-admin']->id,
                'telephone' => '06 12 34 56 78',
                'ville' => 'Paris',
                'est_actif' => true,
                'cgu_accepte' => true,
            ],
            [
                'nom' => 'Dubois',
                'prenom' => 'Pierre',
                'email' => 'pierre.dubois@admin.com',
                'mot_de_passe' => bcrypt('Admin123!'),
                'role_id' => $roles['admin']->id,
                'telephone' => '06 23 45 67 89',
                'ville' => 'Lyon',
                'est_actif' => true,
                'cgu_accepte' => true,
            ],
            [
                'nom' => 'Lefebvre',
                'prenom' => 'Marie',
                'email' => 'marie.lefebvre@example.com',
                'mot_de_passe' => bcrypt('Citoyen123!'),
                'role_id' => $roles['citoyen']->id,
                'telephone' => '06 34 56 78 90',
                'ville' => 'Marseille',
                'est_actif' => true,
                'cgu_accepte' => true,
            ],
            [
                'nom' => 'Moreau',
                'prenom' => 'Lucas',
                'email' => 'lucas.moreau@example.com',
                'mot_de_passe' => bcrypt('Visiteur123!'),
                'role_id' => $roles['visiteur']->id,
                'telephone' => '06 45 67 89 01',
                'ville' => 'Toulouse',
                'est_actif' => true,
                'cgu_accepte' => true,
            ],
        ];

        foreach ($utilisateurs as $utilisateur) {
            Utilisateur::create($utilisateur);
        }

        $this->command->info('✅ 4 utilisateurs de test créés');
        $this->command->info('   - Super Admin: sophie.martin@admin.com / SuperAdmin123!');
        $this->command->info('   - Admin: pierre.dubois@admin.com / Admin123!');
        $this->command->info('   - Citoyen: marie.lefebvre@example.com / Citoyen123!');
        $this->command->info('   - Visiteur: lucas.moreau@example.com / Visiteur123!');
    }
}
