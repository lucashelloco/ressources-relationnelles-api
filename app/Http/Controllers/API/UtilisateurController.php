<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UtilisateurController extends Controller
{
    public function profil(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $request->user()->load('role')
        ]);
    }

    public function updateProfil(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'sometimes|string|max:100',
            'prenom' => 'sometimes|string|max:100',
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string',
            'code_postal' => 'nullable|string|max:10',
            'ville' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $request->user()->update($request->only([
            'nom', 'prenom', 'telephone', 'adresse', 'code_postal', 'ville'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Profil mis à jour avec succès',
            'data' => $request->user()->load('role')
        ]);
    }
}
