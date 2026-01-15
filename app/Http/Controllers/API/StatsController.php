<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Ressource;
use App\Models\Utilisateur;
use App\Models\Activite;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    /**
     * Statistiques globales du site
     */
    public function index(): JsonResponse
    {
        $stats = [
            'ressources' => Ressource::where('statut', 'publie')->count(),
            'utilisateurs' => Utilisateur::where('est_actif', true)->count(),
            'activites' => Activite::whereIn('statut', ['planifie', 'en_cours'])->count(),
            'commentaires' => DB::table('commentaires')->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
