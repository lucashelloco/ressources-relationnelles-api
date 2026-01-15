<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Commentaire;
use App\Models\Ressource;
use App\Models\Utilisateur;
use Carbon\Carbon;

class CommentairesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ressources = Ressource::all();
        $utilisateurs = Utilisateur::all();

        if ($ressources->isEmpty() || $utilisateurs->isEmpty()) {
            $this->command->warn('No resources or users found. Please seed them first.');
            return;
        }

        // Comments for first resource
        $ressource1 = $ressources->first();

        // Parent comment 1 - approved
        $comment1 = Commentaire::create([
            'ressource_id' => $ressource1->id,
            'auteur_id' => $utilisateurs[0]->id,
            'parent_id' => null,
            'contenu' => 'Article très intéressant ! J\'ai appris beaucoup de choses sur la communication dans le couple.',
            'statut' => 'approuve',
            'moderateur_id' => $utilisateurs[1]->id,
            'date_moderation' => Carbon::now()->subDays(2),
            'created_at' => Carbon::now()->subDays(3),
        ]);

        // Reply to comment 1 - approved
        Commentaire::create([
            'ressource_id' => $ressource1->id,
            'auteur_id' => $utilisateurs[1]->id,
            'parent_id' => $comment1->id,
            'contenu' => 'Je suis d\'accord, les conseils sont vraiment pratiques et applicables au quotidien.',
            'statut' => 'approuve',
            'moderateur_id' => $utilisateurs[1]->id,
            'date_moderation' => Carbon::now()->subDays(1),
            'created_at' => Carbon::now()->subDays(2),
        ]);

        // Another reply to comment 1 - approved
        Commentaire::create([
            'ressource_id' => $ressource1->id,
            'auteur_id' => $utilisateurs[2]->id,
            'parent_id' => $comment1->id,
            'contenu' => 'Merci pour ce partage, ça m\'a vraiment aidé dans ma relation.',
            'statut' => 'approuve',
            'moderateur_id' => $utilisateurs[1]->id,
            'date_moderation' => Carbon::now()->subHours(12),
            'created_at' => Carbon::now()->subDays(1),
        ]);

        // Parent comment 2 - approved
        $comment2 = Commentaire::create([
            'ressource_id' => $ressource1->id,
            'auteur_id' => $utilisateurs[3]->id,
            'parent_id' => null,
            'contenu' => 'Excellente ressource ! Est-ce qu\'il y a d\'autres articles sur ce sujet ?',
            'statut' => 'approuve',
            'moderateur_id' => $utilisateurs[1]->id,
            'date_moderation' => Carbon::now()->subHours(6),
            'created_at' => Carbon::now()->subHours(8),
        ]);

        // Reply to comment 2 - approved
        Commentaire::create([
            'ressource_id' => $ressource1->id,
            'auteur_id' => $utilisateurs[0]->id,
            'parent_id' => $comment2->id,
            'contenu' => 'Oui, il y a plusieurs autres articles dans la section "Relations de couple".',
            'statut' => 'approuve',
            'moderateur_id' => $utilisateurs[1]->id,
            'date_moderation' => Carbon::now()->subHours(4),
            'created_at' => Carbon::now()->subHours(5),
        ]);

        // Parent comment 3 - pending moderation (should NOT appear)
        Commentaire::create([
            'ressource_id' => $ressource1->id,
            'auteur_id' => $utilisateurs[4]->id,
            'parent_id' => null,
            'contenu' => 'Ce commentaire est en attente de modération et ne devrait pas apparaître publiquement.',
            'statut' => 'en_attente',
            'created_at' => Carbon::now()->subHours(2),
        ]);

        // Parent comment 4 - rejected (should NOT appear)
        Commentaire::create([
            'ressource_id' => $ressource1->id,
            'auteur_id' => $utilisateurs[3]->id,
            'parent_id' => null,
            'contenu' => 'Ce commentaire a été rejeté.',
            'statut' => 'rejete',
            'moderateur_id' => $utilisateurs[1]->id,
            'date_moderation' => Carbon::now()->subHours(1),
            'raison_rejet' => 'Contenu inapproprié',
            'created_at' => Carbon::now()->subHours(3),
        ]);

        // Add comments to other resources if they exist
        if ($ressources->count() > 1) {
            $ressource2 = $ressources[1];

            Commentaire::create([
                'ressource_id' => $ressource2->id,
                'auteur_id' => $utilisateurs[2]->id,
                'parent_id' => null,
                'contenu' => 'Très utile pour mieux comprendre les dynamiques familiales.',
                'statut' => 'approuve',
                'moderateur_id' => $utilisateurs[1]->id,
                'date_moderation' => Carbon::now()->subDays(1),
                'created_at' => Carbon::now()->subDays(2),
            ]);
        }

        if ($ressources->count() > 2) {
            $ressource3 = $ressources[2];

            Commentaire::create([
                'ressource_id' => $ressource3->id,
                'auteur_id' => $utilisateurs[4]->id,
                'parent_id' => null,
                'contenu' => 'Merci pour ce partage, vraiment éclairant.',
                'statut' => 'approuve',
                'moderateur_id' => $utilisateurs[1]->id,
                'date_moderation' => Carbon::now()->subHours(10),
                'created_at' => Carbon::now()->subHours(12),
            ]);
        }

        $this->command->info('Commentaires created successfully!');
    }
}
