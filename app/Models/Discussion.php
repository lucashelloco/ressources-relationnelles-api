<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discussion extends Model
{
    protected $fillable = [
        'titre',
        'description',
        'ressource_id',
        'createur_id',
        'est_actif',
        'nb_participants',
        'derniere_activite'
    ];

    protected $casts = [
        'est_actif' => 'boolean',
        'derniere_activite' => 'datetime',
    ];

    public function ressource()
    {
        return $this->belongsTo(Ressource::class);
    }

    public function createur()
    {
        return $this->belongsTo(Utilisateur::class, 'createur_id');
    }

    public function messages()
    {
        return $this->hasMany(DiscussionMessage::class)->with('utilisateur')->orderBy('created_at', 'asc');
    }

    public function dernierMessage()
    {
        return $this->hasOne(DiscussionMessage::class)->latestOfMany();
    }
}
