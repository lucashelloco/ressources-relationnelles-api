<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Ressource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class RessourceController extends Controller
{
    /**
     * Liste des ressources avec filtres et pagination
     */
    public function index(Request $request): JsonResponse
    {
        $query = Ressource::with(['auteur', 'categories', 'tags'])
            ->where('statut', 'publie')
            ->where('niveau_partage', 'public');

        // Filtres
        if ($request->has('type_ressource')) {
            $query->where('type_ressource', $request->type_ressource);
        }

        if ($request->has('type_relation')) {
            $query->where('type_relation', $request->type_relation);
        }

        if ($request->has('categorie_id')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->categorie_id);
            });
        }

        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('titre', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Tri
        $orderBy = $request->get('order_by', 'date_publication');
        $order = $request->get('order', 'desc');
        $query->orderBy($orderBy, $order);

        // Pagination
        $ressources = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $ressources
        ]);
    }

    /**
     * Afficher une ressource spécifique
     */
    public function show(int $id): JsonResponse
    {
        $ressource = Ressource::with([
            'auteur',
            'moderateur',
            'categories',
            'tags',
            'commentaires.auteur'
        ])->findOrFail($id);

        // Incrémenter le compteur de vues
        $ressource->increment('nb_vues');

        return response()->json([
            'success' => true,
            'data' => $ressource
        ]);
    }

    /**
     * Créer une nouvelle ressource
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'contenu' => 'nullable|string',
            'type_ressource' => 'required|in:article,video,audio,document,lien,infographie',
            'type_relation' => 'required|in:familiale,amicale,amoureuse,professionnelle,therapeutique,autre',
            'niveau' => 'nullable|in:debutant,intermediaire,avance,tous',
            'statut' => 'nullable|in:brouillon,en_attente,publie,archive,rejete',
            'niveau_partage' => 'nullable|in:prive,membres,public',
            'duree_estimee' => 'nullable|integer|min:1',
            'url_image_couverture' => 'nullable|url',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $ressource = Ressource::create([
            'titre' => $request->titre,
            'description' => $request->description,
            'contenu' => $request->contenu,
            'type_ressource' => $request->type_ressource,
            'type_relation' => $request->type_relation,
            'niveau' => $request->niveau,
            'statut' => $request->get('statut', 'brouillon'),
            'niveau_partage' => $request->get('niveau_partage', 'public'),
            'auteur_id' => auth()->id(),
            'duree_estimee' => $request->duree_estimee,
            'url_image_couverture' => $request->url_image_couverture,
            'date_creation' => now(),
        ]);

        // Attacher les catégories et tags
        if ($request->has('categories')) {
            $ressource->categories()->attach($request->categories);
        }

        if ($request->has('tags')) {
            $ressource->tags()->attach($request->tags);
        }

        return response()->json([
            'success' => true,
            'message' => 'Ressource créée avec succès',
            'data' => $ressource->load(['categories', 'tags'])
        ], 201);
    }

    /**
     * Mettre à jour une ressource
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $ressource = Ressource::findOrFail($id);

        // Vérifier que l'utilisateur est l'auteur
        if ($ressource->auteur_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Non autorisé'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'titre' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'contenu' => 'nullable|string',
            'type_ressource' => 'sometimes|required|in:article,video,audio,document,lien,infographie',
            'type_relation' => 'sometimes|required|in:familiale,amicale,amoureuse,professionnelle,therapeutique,autre',
            'niveau' => 'nullable|in:debutant,intermediaire,avance,tous',
            'statut' => 'nullable|in:brouillon,en_attente,publie,archive,rejete',
            'niveau_partage' => 'nullable|in:prive,membres,public',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $ressource->update($request->except(['categories', 'tags']));

        // Synchroniser les catégories et tags si fournis
        if ($request->has('categories')) {
            $ressource->categories()->sync($request->categories);
        }

        if ($request->has('tags')) {
            $ressource->tags()->sync($request->tags);
        }

        return response()->json([
            'success' => true,
            'message' => 'Ressource mise à jour avec succès',
            'data' => $ressource->load(['categories', 'tags'])
        ]);
    }

    /**
     * Supprimer une ressource (soft delete)
     */
    public function destroy(int $id): JsonResponse
    {
        $ressource = Ressource::findOrFail($id);

        // Vérifier que l'utilisateur est l'auteur
        if ($ressource->auteur_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Non autorisé'
            ], 403);
        }

        $ressource->delete();

        return response()->json([
            'success' => true,
            'message' => 'Ressource supprimée avec succès'
        ]);
    }

    /**
     * Ajouter/Retirer des favoris
     */
    public function toggleFavori(int $id): JsonResponse
    {
        $ressource = Ressource::findOrFail($id);
        $utilisateur = auth()->user();

        if ($utilisateur->favoris()->where('ressource_id', $id)->exists()) {
            $utilisateur->favoris()->detach($id);
            $ressource->decrement('nb_favoris');
            $message = 'Ressource retirée des favoris';
        } else {
            $utilisateur->favoris()->attach($id, ['date_ajout' => now()]);
            $ressource->increment('nb_favoris');
            $message = 'Ressource ajoutée aux favoris';
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    /**
     * Publier une ressource (passer de brouillon à en_attente)
     */
    public function publier(int $id): JsonResponse
    {
        $ressource = Ressource::findOrFail($id);

        // Vérifier que l'utilisateur est l'auteur
        if ($ressource->auteur_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Non autorisé'
            ], 403);
        }

        if ($ressource->statut !== 'brouillon') {
            return response()->json([
                'success' => false,
                'message' => 'Seules les ressources en brouillon peuvent être publiées'
            ], 400);
        }

        $ressource->update([
            'statut' => 'publie',
            'date_publication' => now(),
            'date_modification' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ressource publiée avec succès',
            'data' => $ressource
        ]);
    }
}