<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            'communication bienveillante',
            'écoute active',
            'empathie',
            'gestion des émotions',
            'conflit',
            'médiation',
            'couple',
            'famille',
            'parentalité',
            'enfants',
            'adolescents',
            'amitié',
            'travail',
            'collaboration',
            'leadership',
            'assertivité',
            'confiance en soi',
            'estime de soi',
            'développement personnel',
            'bien-être',
            'stress',
            'anxiété',
            'pleine conscience',
            'méditation',
            'psychologie',
            'coaching',
            'thérapie',
            'CNV',
            'PNL',
            'intelligence émotionnelle'
        ];

        $data = [];
        foreach ($tags as $tag) {
            $data[] = [
                'nom' => $tag,
                'slug' => Str::slug($tag),
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        DB::table('tags')->insert($data);
    }
}
