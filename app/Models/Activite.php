<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activite extends Model
{
    protected $fillable = [
        'titre',
        'description',
        'type',
        'statut',
        'date_debut',
        'date_fin',
        'lieu',
        'en_ligne',
        'lien_visio',
        'nb_participants_max',
        'nb_participants_actuels',
        'organisateur_id'
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
        'en_ligne' => 'boolean',
        'nb_participants_max' => 'integer',
        'nb_participants_actuels' => 'integer'
    ];

    protected $appends = ['places_restantes'];

    public function getPlacesRestantesAttribute()
    {
        return $this->nb_participants_max - $this->nb_participants_actuels;
    }

    public function organisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'organisateur_id');
    }

    public function participants()
    {
        return $this->belongsToMany(Utilisateur::class, 'participants', 'activite_id', 'utilisateur_id')
            ->withPivot('statut', 'date_inscription')
            ->withTimestamps();
    }
}
