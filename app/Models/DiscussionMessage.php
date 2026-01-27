<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscussionMessage extends Model
{
    protected $fillable = [
        'contenu',
        'discussion_id',
        'utilisateur_id',
        'est_lu',
        'lu_a'
    ];

    protected $casts = [
        'est_lu' => 'boolean',
        'lu_a' => 'datetime',
    ];

    public function discussion()
    {
        return $this->belongsTo(Discussion::class);
    }

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class);
    }
}
