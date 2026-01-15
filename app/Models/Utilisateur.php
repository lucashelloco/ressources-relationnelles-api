<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Utilisateur extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'mot_de_passe',
        'telephone',
        'adresse',
        'code_postal',
        'ville',
        'role_id',
        'est_actif',
        'cgu_accepte'
    ];

    protected $hidden = ['mot_de_passe', 'remember_token'];

    protected $casts = [
        'est_actif' => 'boolean',
        'cgu_accepte' => 'boolean',
        'date_inscription' => 'datetime'
    ];

    public function getAuthPassword()
    {
        return $this->mot_de_passe;
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function ressources()
    {
        return $this->hasMany(Ressource::class, 'auteur_id');
    }

    public function favoris()
    {
        return $this->belongsToMany(Ressource::class, 'favoris')
            ->withPivot('date_ajout')
            ->withTimestamps();
    }

    public function activites()
    {
        return $this->belongsToMany(Activite::class, 'participants', 'utilisateur_id', 'activite_id')
            ->withPivot('statut', 'date_inscription')
            ->withTimestamps();
    }

    /**
     * Check if user has a specific permission
     */
    public function hasPermission(string $permission): bool
    {
        if (!$this->role) {
            return false;
        }

        $permissions = $this->role->permissions ?? [];

        return in_array($permission, $permissions);
    }
}
