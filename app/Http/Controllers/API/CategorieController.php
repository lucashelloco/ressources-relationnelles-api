<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Categorie;

class CategorieController extends Controller
{
    public function index()
    {
        $categories = Categorie::where('est_active', true)
            ->orderBy('ordre')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    public function show($slug)
    {
        $categorie = Categorie::where('slug', $slug)
            ->where('est_active', true)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $categorie
        ]);
    }
}
