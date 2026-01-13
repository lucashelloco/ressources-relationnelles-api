<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categorie;

class CategorieSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'nom' => 'Communication',
                'slug' => 'communication',
                'description' => 'Améliorer sa communication verbale et non-verbale',
                'icone' => '💬',
                'couleur' => '#3B82F6',
                'ordre' => 1,
                'est_active' => true,
            ],
            [
                'nom' => 'Couple',
                'slug' => 'couple',
                'description' => 'Relations de couple et vie amoureuse',
                'icone' => '❤️',
                'couleur' => '#EF4444',
                'ordre' => 2,
                'est_active' => true,
            ],
            [
                'nom' => 'Famille',
                'slug' => 'famille',
                'description' => 'Relations familiales et parentalité',
                'icone' => '👨‍👩‍👧‍👦',
                'couleur' => '#10B981',
                'ordre' => 3,
                'est_active' => true,
            ],
            [
                'nom' => 'Amitié',
                'slug' => 'amitie',
                'description' => 'Cultiver et entretenir ses amitiés',
                'icone' => '🤝',
                'couleur' => '#F59E0B',
                'ordre' => 4,
                'est_active' => true,
            ],
            [
                'nom' => 'Travail',
                'slug' => 'travail',
                'description' => 'Relations professionnelles et collaboratives',
                'icone' => '💼',
                'couleur' => '#6366F1',
                'ordre' => 5,
                'est_active' => true,
            ],
        ];

        foreach ($categories as $categorie) {
            Categorie::create($categorie);
        }

        $this->command->info('✅ 5 catégories créées');
    }
}
