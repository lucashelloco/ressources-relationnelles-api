<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ressource;
use App\Models\Utilisateur;

class RessourceSeeder extends Seeder
{
    public function run(): void
    {
        $auteur = Utilisateur::where('email', 'marie.lefebvre@example.com')->first();

        $ressources = [
            [
                'titre' => 'Communication non-violente : les bases',
                'description' => 'Apprenez les fondamentaux de la CNV pour améliorer vos relations',
                'contenu' => 'La Communication Non-Violente (CNV) est une méthode de communication développée par Marshall Rosenberg. Elle repose sur quatre étapes : observation, sentiment, besoin, demande.',
                'type_ressource' => 'article',
                'niveau' => 'debutant',
                'statut' => 'publie',
                'niveau_partage' => 'public',
                'auteur_id' => $auteur->id,
                'date_creation' => now(),
                'date_publication' => now(),
            ],
            [
                'titre' => 'Gérer les conflits dans le couple',
                'description' => 'Des outils concrets pour résoudre les désaccords',
                'contenu' => 'Les conflits sont naturels dans une relation. L\'important est d\'apprendre à les gérer de manière constructive.',
                'type_ressource' => 'guide',
                'niveau' => 'intermediaire',
                'statut' => 'publie',
                'niveau_partage' => 'public',
                'auteur_id' => $auteur->id,
                'date_creation' => now(),
                'date_publication' => now(),
            ],
            [
                'titre' => "L'écoute active en pratique",
                'description' => 'Techniques avancées pour vraiment comprendre l\'autre',
                'contenu' => 'L\'écoute active nécessite une présence totale et une véritable attention à l\'autre.',
                'type_ressource' => 'video',
                'niveau' => 'avance',
                'statut' => 'publie',
                'niveau_partage' => 'public',
                'url_image' => 'https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?w=500',
                'auteur_id' => $auteur->id,
                'date_creation' => now(),
                'date_publication' => now(),
            ],
        ];

        foreach ($ressources as $ressource) {
            Ressource::create($ressource);
        }

        $this->command->info('✅ 3 ressources créées');
    }
}
