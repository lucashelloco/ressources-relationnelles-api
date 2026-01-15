<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Routes publiques
Route::prefix('v1')->group(function () {

    // Authentification
    Route::post('/login', [App\Http\Controllers\API\AuthController::class, 'login']);
    Route::post('/register', [App\Http\Controllers\API\AuthController::class, 'register']);
    Route::post('/forgot-password', [App\Http\Controllers\API\AuthController::class, 'forgotPassword']);

    // Statistiques
    Route::get('/stats', [App\Http\Controllers\API\StatsController::class, 'index']);

    // Configuration (options pour formulaires)
    Route::get('/config', [App\Http\Controllers\API\ConfigController::class, 'index']);

    // Ressources publiques (lecture seule)
    Route::get('/ressources', [App\Http\Controllers\API\RessourceController::class, 'index']);
    Route::get('/ressources/{id}', [App\Http\Controllers\API\RessourceController::class, 'show']);

    // Catégories publiques
    Route::get('/categories', [App\Http\Controllers\API\CategorieController::class, 'index']);

    // Tags publics
    Route::get('/tags', [App\Http\Controllers\API\TagController::class, 'index']);

    // Activités publiques
    Route::get('/activites', [App\Http\Controllers\API\ActiviteController::class, 'index']);
    Route::get('/activites/{id}', [App\Http\Controllers\API\ActiviteController::class, 'show']);

    // Commentaires publics (approuvés uniquement)
    Route::get('/ressources/{ressourceId}/commentaires', [App\Http\Controllers\API\CommentaireController::class, 'index']);
});

// Routes protégées (nécessitent authentification)
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    
    // Authentification
    Route::post('/logout', [App\Http\Controllers\API\AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // Profil utilisateur
    Route::prefix('profil')->group(function () {
        Route::get('/', [App\Http\Controllers\API\UtilisateurController::class, 'profil']);
        Route::put('/', [App\Http\Controllers\API\UtilisateurController::class, 'updateProfil']);
    });
    
    // Ressources (CRUD complet)
    Route::prefix('ressources')->group(function () {
        Route::post('/', [App\Http\Controllers\API\RessourceController::class, 'store']);
        Route::put('/{id}', [App\Http\Controllers\API\RessourceController::class, 'update']);
        Route::delete('/{id}', [App\Http\Controllers\API\RessourceController::class, 'destroy']);
        
        // Actions sur les ressources
        Route::post('/{id}/favoris', [App\Http\Controllers\API\RessourceController::class, 'toggleFavori']);
        Route::post('/{id}/publier', [App\Http\Controllers\API\RessourceController::class, 'publier']);
    });
    
    // Activités (CRUD complet)
    Route::prefix('activites')->group(function () {
        Route::post('/', [App\Http\Controllers\API\ActiviteController::class, 'store']);
        Route::put('/{id}', [App\Http\Controllers\API\ActiviteController::class, 'update']);
        Route::delete('/{id}', [App\Http\Controllers\API\ActiviteController::class, 'destroy']);

        // Gestion des participants
        Route::get('/{id}/participants', [App\Http\Controllers\API\ActiviteController::class, 'participants']);
        Route::post('/{id}/inscription', [App\Http\Controllers\API\ActiviteController::class, 'inscrire']);
        Route::delete('/{id}/inscription', [App\Http\Controllers\API\ActiviteController::class, 'desinscrire']);
    });

    // Commentaires (CRUD)
    Route::prefix('ressources/{ressourceId}/commentaires')->group(function () {
        Route::post('/', [App\Http\Controllers\API\CommentaireController::class, 'store']);
        Route::put('/{id}', [App\Http\Controllers\API\CommentaireController::class, 'update']);
        Route::delete('/{id}', [App\Http\Controllers\API\CommentaireController::class, 'destroy']);
    });

    // Modération des commentaires (modérateurs uniquement)
    Route::middleware('can:moderer_contenu')->prefix('commentaires')->group(function () {
        Route::get('/en-attente', [App\Http\Controllers\API\CommentaireController::class, 'enAttente']);
        Route::post('/{id}/approuver', [App\Http\Controllers\API\CommentaireController::class, 'approuver']);
        Route::post('/{id}/rejeter', [App\Http\Controllers\API\CommentaireController::class, 'rejeter']);
    });
});