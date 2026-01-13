<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Inscription
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'email' => 'required|email|unique:utilisateurs,email',
            'mot_de_passe' => 'required|string|min:8',
            'cgu_accepte' => 'required|boolean|accepted',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $utilisateur = Utilisateur::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'mot_de_passe' => Hash::make($request->mot_de_passe),
            'role_id' => 3, // Membre par défaut
            'est_actif' => true,
            'email_verifie' => false,
            'cgu_accepte' => $request->cgu_accepte,
            'date_inscription' => now(),
        ]);

        $token = $utilisateur->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Compte créé avec succès',
            'token' => $token,
            'user' => $utilisateur->load('role')
        ], 201);
    }

    /**
     * Connexion
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'mot_de_passe' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $utilisateur = Utilisateur::where('email', $request->email)->first();

        if (!$utilisateur || !Hash::check($request->mot_de_passe, $utilisateur->mot_de_passe)) {
            return response()->json([
                'success' => false,
                'message' => 'Email ou mot de passe incorrect'
            ], 401);
        }

        if (!$utilisateur->est_actif) {
            return response()->json([
                'success' => false,
                'message' => 'Votre compte est désactivé'
            ], 403);
        }

        // Mettre à jour la date de dernière connexion
        $utilisateur->update(['date_derniere_connexion' => now()]);

        $token = $utilisateur->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Connexion réussie',
            'token' => $token,
            'user' => $utilisateur->load('role')
        ]);
    }

    /**
     * Déconnexion
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Déconnexion réussie'
        ]);
    }

    /**
     * Mot de passe oublié
     */
    public function forgotPassword(Request $request)
    {
        // TODO: Implémenter l'envoi d'email de réinitialisation
        return response()->json([
            'success' => true,
            'message' => 'Email de réinitialisation envoyé'
        ]);
    }
}