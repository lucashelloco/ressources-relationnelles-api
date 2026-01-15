<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'nom' => 'Communication',
                'slug' => 'communication',
                'description' => 'Ressources sur la communication bienveillante, l\'écoute active et l\'expression des émotions',
                'icone' => '💬',
                'couleur' => '#6A89CC',
                'ordre' => 1,
                'est_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Relations Familiales',
                'slug' => 'relations-familiales',
                'description' => 'Guides et conseils pour améliorer les relations parents-enfants et la dynamique familiale',
                'icone' => '👨‍👩‍👧‍👦',
                'couleur' => '#F8B500',
                'ordre' => 2,
                'est_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Relations Amoureuses',
                'slug' => 'relations-amoureuses',
                'description' => 'Ressources sur le couple, l\'amour et l\'intimité émotionnelle',
                'icone' => '❤️',
                'couleur' => '#E55039',
                'ordre' => 3,
                'est_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Amitiés',
                'slug' => 'amities',
                'description' => 'Cultiver et maintenir des amitiés saines et enrichissantes',
                'icone' => '🤝',
                'couleur' => '#78E08F',
                'ordre' => 4,
                'est_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Relations Professionnelles',
                'slug' => 'relations-professionnelles',
                'description' => 'Communication et collaboration efficaces au travail',
                'icone' => '💼',
                'couleur' => '#4A69BD',
                'ordre' => 5,
                'est_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Gestion des Conflits',
                'slug' => 'gestion-conflits',
                'description' => 'Techniques de résolution de conflits et médiation',
                'icone' => '⚖️',
                'couleur' => '#EE5A6F',
                'ordre' => 6,
                'est_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Développement Personnel',
                'slug' => 'developpement-personnel',
                'description' => 'Connaissance de soi, estime de soi et croissance personnelle',
                'icone' => '🌱',
                'couleur' => '#38ADA9',
                'ordre' => 7,
                'est_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Émotions et Bien-être',
                'slug' => 'emotions-bien-etre',
                'description' => 'Gestion des émotions, stress et santé mentale',
                'icone' => '🧘',
                'couleur' => '#B8E994',
                'ordre' => 8,
                'est_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('categories')->insert($categories);
    }
}
