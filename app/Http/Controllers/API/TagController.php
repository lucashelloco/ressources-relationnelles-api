<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Tag;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::orderBy('nom')->get();

        return response()->json([
            'success' => true,
            'data' => $tags
        ]);
    }
}
