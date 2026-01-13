<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['nom', 'slug'];

    public function ressources()
    {
        return $this->belongsToMany(Ressource::class, 'ressource_tag');
    }
}
