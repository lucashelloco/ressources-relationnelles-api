<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            ['nom' => 'Écoute active', 'slug' => 'ecoute-active'],
            ['nom' => 'Gestion des conflits', 'slug' => 'gestion-conflits'],
            ['nom' => 'Empathie', 'slug' => 'empathie'],
            ['nom' => 'Communication non-violente', 'slug' => 'cnv'],
            ['nom' => 'Assertivité', 'slug' => 'assertivite'],
            ['nom' => 'Confiance', 'slug' => 'confiance'],
            ['nom' => 'Respect', 'slug' => 'respect'],
            ['nom' => 'Dialogue', 'slug' => 'dialogue'],
        ];

        foreach ($tags as $tag) {
            Tag::create($tag);
        }

        $this->command->info('✅ 8 tags créés');
    }
}
