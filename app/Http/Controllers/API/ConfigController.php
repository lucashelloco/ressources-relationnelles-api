<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ConfigController extends Controller
{
    /**
     * Retourne toutes les options de configuration pour les formulaires
     */
    public function index(): JsonResponse
    {
        $config = [
            'types_ressource' => [
                ['value' => 'article', 'label' => 'Article'],
                ['value' => 'video', 'label' => 'Vidéo'],
                ['value' => 'audio', 'label' => 'Audio'],
                ['value' => 'podcast', 'label' => 'Podcast'],
                ['value' => 'document', 'label' => 'Document'],
                ['value' => 'lien', 'label' => 'Lien'],
                ['value' => 'infographie', 'label' => 'Infographie'],
                ['value' => 'guide', 'label' => 'Guide'],
                ['value' => 'etude', 'label' => 'Étude'],
            ],
            'types_relation' => [
                ['value' => 'familiale', 'label' => 'Familiale'],
                ['value' => 'amicale', 'label' => 'Amicale'],
                ['value' => 'amoureuse', 'label' => 'Amoureuse'],
                ['value' => 'professionnelle', 'label' => 'Professionnelle'],
                ['value' => 'therapeutique', 'label' => 'Thérapeutique'],
                ['value' => 'autre', 'label' => 'Autre'],
            ],
            'niveaux' => [
                ['value' => 'debutant', 'label' => 'Débutant'],
                ['value' => 'intermediaire', 'label' => 'Intermédiaire'],
                ['value' => 'avance', 'label' => 'Avancé'],
                ['value' => 'tous', 'label' => 'Tous niveaux'],
            ],
            'niveaux_partage' => [
                ['value' => 'prive', 'label' => 'Privé (vous uniquement)'],
                ['value' => 'membres', 'label' => 'Membres inscrits'],
                ['value' => 'public', 'label' => 'Public'],
            ],
            'types_activite' => [
                ['value' => 'atelier', 'label' => 'Atelier'],
                ['value' => 'conference', 'label' => 'Conférence'],
                ['value' => 'groupe_parole', 'label' => 'Groupe de parole'],
                ['value' => 'webinaire', 'label' => 'Webinaire'],
                ['value' => 'formation', 'label' => 'Formation'],
            ],
            'statuts_activite' => [
                ['value' => 'planifie', 'label' => 'Planifié'],
                ['value' => 'en_cours', 'label' => 'En cours'],
                ['value' => 'termine', 'label' => 'Terminé'],
                ['value' => 'annule', 'label' => 'Annulé'],
            ],
        ];

        return response()->json([
            'success' => true,
            'data' => $config
        ]);
    }
}
