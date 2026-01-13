<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ressource extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'titre',
        'description',
        'contenu',
        'type_ressource',
        'type_relation',
        'niveau',
        'statut',
        'niveau_partage',
        'url_externe',
        'url_image',
        'duree_lecture',
        'auteur_id',
        'date_creation',
        'date_publication',
        'nb_vues',
        'nb_favoris'
    ];

    protected $casts = [
        'date_creation' => 'datetime',
        'date_publication' => 'datetime',
        'nb_vues' => 'integer',
        'nb_favoris' => 'integer'
    ];

    public function auteur()
    {
        return $this->belongsTo(Utilisateur::class, 'auteur_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Categorie::class, 'categorie_ressource');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'ressource_tag');
    }
}
