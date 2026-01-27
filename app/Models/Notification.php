<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'type',
        'message',
        'data',
        'action_url',
        'utilisateur_id',
        'est_lue',
        'date_lecture'
    ];

    protected $casts = [
        'data' => 'array',
        'est_lue' => 'boolean',
        'date_lecture' => 'datetime',
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class);
    }

    public function markAsRead()
    {
        $this->update([
            'est_lue' => true,
            'date_lecture' => now()
        ]);
    }
}
