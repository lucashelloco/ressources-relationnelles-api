<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Commentaire;
use App\Models\Ressource;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class CommentaireController extends Controller
{
    /**
     * Liste des commentaires d'une ressource (approuvés uniquement pour les visiteurs)
     */
    public function index(int $ressourceId): JsonResponse
    {
        $ressource = Ressource::findOrFail($ressourceId);

        $commentaires = Commentaire::where('ressource_id', $ressourceId)
            ->premierNiveau()
            ->approuve()
            ->with(['auteur', 'reponsesApprouvees'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $commentaires
        ]);
    }

    /**
     * Créer un commentaire
     */
    public function store(Request $request, int $ressourceId): JsonResponse
    {
        $ressource = Ressource::findOrFail($ressourceId);

        $validator = Validator::make($request->all(), [
            'contenu' => 'required|string|min:10|max:2000',
            'parent_id' => 'nullable|exists:commentaires,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Vérifier que le parent appartient bien à la même ressource
        if ($request->parent_id) {
            $parent = Commentaire::find($request->parent_id);
            if ($parent->ressource_id !== $ressourceId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Le commentaire parent n\'appartient pas à cette ressource'
                ], 400);
            }
        }

        $commentaire = Commentaire::create([
            'ressource_id' => $ressourceId,
            'auteur_id' => auth()->id(),
            'parent_id' => $request->parent_id,
            'contenu' => $request->contenu,
            'statut' => 'en_attente'
        ]);

        // Notify admins about new comment to moderate
        NotificationService::notifyAdminsNewComment($commentaire->load('auteur'));

        return response()->json([
            'success' => true,
            'message' => 'Commentaire envoyé. Il sera visible après modération.',
            'data' => $commentaire->load('auteur')
        ], 201);
    }

    /**
     * Modifier son propre commentaire (uniquement si en_attente)
     */
    public function update(Request $request, int $ressourceId, int $id): JsonResponse
    {
        $commentaire = Commentaire::where('ressource_id', $ressourceId)
            ->findOrFail($id);

        // Vérifier que l'utilisateur est l'auteur
        if ($commentaire->auteur_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Non autorisé'
            ], 403);
        }

        // On ne peut modifier que les commentaires en attente
        if ($commentaire->statut !== 'en_attente') {
            return response()->json([
                'success' => false,
                'message' => 'Vous ne pouvez modifier que les commentaires en attente de modération'
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'contenu' => 'required|string|min:10|max:2000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $commentaire->update([
            'contenu' => $request->contenu
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Commentaire modifié',
            'data' => $commentaire
        ]);
    }

    /**
     * Supprimer son propre commentaire
     */
    public function destroy(int $ressourceId, int $id): JsonResponse
    {
        $commentaire = Commentaire::where('ressource_id', $ressourceId)
            ->findOrFail($id);

        // Vérifier que l'utilisateur est l'auteur
        if ($commentaire->auteur_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Non autorisé'
            ], 403);
        }

        $commentaire->delete();

        return response()->json([
            'success' => true,
            'message' => 'Commentaire supprimé'
        ]);
    }

    /**
     * Liste des commentaires en attente de modération (modérateurs uniquement)
     */
    public function enAttente(): JsonResponse
    {
        $commentaires = Commentaire::enAttente()
            ->with(['auteur', 'ressource'])
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $commentaires
        ]);
    }

    /**
     * Approuver un commentaire (modérateurs uniquement)
     */
    public function approuver(int $id): JsonResponse
    {
        $commentaire = Commentaire::findOrFail($id);

        $commentaire->update([
            'statut' => 'approuve',
            'moderateur_id' => auth()->id(),
            'date_moderation' => now()
        ]);

        // Delete all notifications related to this comment
        \App\Models\Notification::where('type', 'comment_moderation')
            ->where('data->commentaire_id', $commentaire->id)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Commentaire approuvé',
            'data' => $commentaire
        ]);
    }

    /**
     * Rejeter un commentaire (modérateurs uniquement)
     */
    public function rejeter(Request $request, int $id): JsonResponse
    {
        $commentaire = Commentaire::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'raison' => 'required|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $commentaire->update([
            'statut' => 'rejete',
            'moderateur_id' => auth()->id(),
            'date_moderation' => now(),
            'raison_rejet' => $request->raison
        ]);

        // Delete all notifications related to this comment
        \App\Models\Notification::where('type', 'comment_moderation')
            ->where('data->commentaire_id', $commentaire->id)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Commentaire rejeté',
            'data' => $commentaire
        ]);
    }
}
