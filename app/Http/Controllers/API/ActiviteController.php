<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Activite;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class ActiviteController extends Controller
{
    /**
     * Liste des activités
     */
    public function index(Request $request): JsonResponse
    {
        $query = Activite::with(['organisateur', 'participants']);

        // Filtres
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->has('en_ligne')) {
            $query->where('en_ligne', $request->boolean('en_ligne'));
        }

        // Tri
        $query->orderBy('date_debut', 'asc');

        $activites = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $activites
        ]);
    }

    /**
     * Afficher une activité
     */
    public function show(int $id): JsonResponse
    {
        $activite = Activite::with([
            'organisateur',
            'participants'
        ])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $activite
        ]);
    }

    /**
     * Créer une activité
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:atelier,conference,groupe_parole,webinaire,formation',
            'date_debut' => 'required|date|after:now',
            'date_fin' => 'nullable|date|after:date_debut',
            'lieu' => 'required_if:en_ligne,false|string|max:255',
            'en_ligne' => 'required|boolean',
            'lien_visio' => 'required_if:en_ligne,true|nullable|url',
            'nb_participants_max' => 'nullable|integer|min:2',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $activite = Activite::create([
            'titre' => $request->titre,
            'description' => $request->description,
            'type' => $request->type,
            'statut' => 'planifie',
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'lieu' => $request->lieu,
            'en_ligne' => $request->en_ligne,
            'lien_visio' => $request->lien_visio,
            'nb_participants_max' => $request->nb_participants_max,
            'nb_participants_actuels' => 1,
            'organisateur_id' => auth()->id(),
        ]);

        // L'organisateur est automatiquement participant
        $activite->participants()->attach(auth()->id(), [
            'date_inscription' => now(),
            'statut' => 'confirme'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Activité créée avec succès',
            'data' => $activite->load('organisateur')
        ], 201);
    }

    /**
     * Mettre à jour une activité
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $activite = Activite::findOrFail($id);

        // Vérifier que l'utilisateur est l'organisateur
        if ($activite->organisateur_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Non autorisé'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'titre' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'sometimes|required|in:atelier,conference,groupe_parole,webinaire,formation',
            'date_debut' => 'sometimes|required|date',
            'date_fin' => 'nullable|date|after:date_debut',
            'statut' => 'sometimes|in:planifie,en_cours,termine,annule',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $activite->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Activité mise à jour avec succès',
            'data' => $activite
        ]);
    }

    /**
     * Supprimer une activité
     */
    public function destroy(int $id): JsonResponse
    {
        $activite = Activite::findOrFail($id);

        // Vérifier que l'utilisateur est l'organisateur
        if ($activite->organisateur_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Non autorisé'
            ], 403);
        }

        $activite->delete();

        return response()->json([
            'success' => true,
            'message' => 'Activité supprimée avec succès'
        ]);
    }

    /**
     * S'inscrire à une activité
     */
    public function inscrire(int $id): JsonResponse
    {
        $activite = Activite::findOrFail($id);
        $utilisateur = auth()->user();

        // Vérifier si l'utilisateur est déjà inscrit
        if ($activite->participants()->where('utilisateur_id', $utilisateur->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Vous êtes déjà inscrit à cette activité'
            ], 400);
        }

        // Inscrire l'utilisateur
        $activite->participants()->attach($utilisateur->id, [
            'date_inscription' => now(),
            'statut' => 'inscrit'
        ]);

        $activite->increment('nb_participants_actuels');

        return response()->json([
            'success' => true,
            'message' => 'Inscription réussie'
        ]);
    }

    /**
     * Se désinscrire d'une activité
     */
    public function desinscrire(int $id): JsonResponse
    {
        $activite = Activite::findOrFail($id);
        $utilisateur = auth()->user();

        // Vérifier si l'utilisateur est l'organisateur
        if ($activite->organisateur_id === $utilisateur->id) {
            return response()->json([
                'success' => false,
                'message' => 'L\'organisateur ne peut pas se désinscrire'
            ], 400);
        }

        // Désinscrire l'utilisateur
        $activite->participants()->detach($utilisateur->id);
        $activite->decrement('nb_participants_actuels');

        return response()->json([
            'success' => true,
            'message' => 'Désinscription réussie'
        ]);
    }

    /**
     * Liste des participants d'une activité
     */
    public function participants(int $id): JsonResponse
    {
        $activite = Activite::findOrFail($id);

        $participants = $activite->participants()
            ->withPivot('statut', 'date_inscription')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $participants
        ]);
    }
}