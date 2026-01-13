<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'nom' => 'Super Administrateur',
                'slug' => 'super-admin',
                'description' => 'Accès complet à toutes les fonctionnalités du système'
            ],
            [
                'nom' => 'Administrateur',
                'slug' => 'admin',
                'description' => 'Gestion des contenus et des utilisateurs'
            ],
            [
                'nom' => 'Citoyen Connecté',
                'slug' => 'citoyen',
                'description' => 'Utilisateur connecté avec accès complet aux ressources'
            ],
            [
                'nom' => 'Visiteur',
                'slug' => 'visiteur',
                'description' => 'Utilisateur non connecté avec accès limité'
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }

        $this->command->info('✅ 4 rôles créés');
    }
}
