<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Commentaire extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'ressource_id',
        'auteur_id',
        'parent_id',
        'contenu',
        'statut',
        'moderateur_id',
        'date_moderation',
        'raison_rejet'
    ];

    protected $casts = [
        'date_moderation' => 'datetime',
    ];

    protected $appends = ['nb_reponses'];

    /**
     * Auteur du commentaire
     */
    public function auteur()
    {
        return $this->belongsTo(Utilisateur::class, 'auteur_id');
    }

    /**
     * Ressource commentée
     */
    public function ressource()
    {
        return $this->belongsTo(Ressource::class, 'ressource_id');
    }

    /**
     * Modérateur qui a validé/rejeté
     */
    public function moderateur()
    {
        return $this->belongsTo(Utilisateur::class, 'moderateur_id');
    }

    /**
     * Commentaire parent (pour les réponses)
     */
    public function parent()
    {
        return $this->belongsTo(Commentaire::class, 'parent_id');
    }

    /**
     * Réponses au commentaire
     */
    public function reponses()
    {
        return $this->hasMany(Commentaire::class, 'parent_id');
    }

    /**
     * Réponses approuvées uniquement
     */
    public function reponsesApprouvees()
    {
        return $this->hasMany(Commentaire::class, 'parent_id')
            ->where('statut', 'approuve')
            ->with(['auteur', 'reponsesApprouvees']);
    }

    /**
     * Nombre de réponses
     */
    public function getNbReponsesAttribute()
    {
        return $this->reponses()->where('statut', 'approuve')->count();
    }

    /**
     * Scope pour les commentaires approuvés
     */
    public function scopeApprouve($query)
    {
        return $query->where('statut', 'approuve');
    }

    /**
     * Scope pour les commentaires en attente
     */
    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    /**
     * Scope pour les commentaires de premier niveau (pas de réponses)
     */
    public function scopePremierNiveau($query)
    {
        return $query->whereNull('parent_id');
    }
}
