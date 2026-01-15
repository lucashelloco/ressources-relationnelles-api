<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['nom', 'slug', 'description', 'permissions'];

    protected $casts = [
        'permissions' => 'array',
    ];

    public function utilisateurs()
    {
        return $this->hasMany(Utilisateur::class);
    }
}
