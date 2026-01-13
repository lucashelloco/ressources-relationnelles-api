<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    protected $fillable = [
        'nom',
        'slug',
        'description',
        'icone',
        'couleur',
        'ordre',
        'est_active'
    ];

    protected $casts = [
        'est_active' => 'boolean',
        'ordre' => 'integer'
    ];

    public function ressources()
    {
        return $this->belongsToMany(Ressource::class, 'categorie_ressource');
    }
}
