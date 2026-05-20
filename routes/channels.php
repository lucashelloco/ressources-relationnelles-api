<?php

use Illuminate\Support\Facades\Broadcast;

// Canal privé par utilisateur pour les notifications
Broadcast::channel('App.Models.Utilisateur.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Canal privé pour une discussion spécifique
Broadcast::channel('discussion.{discussionId}', function ($user, $discussionId) {
    // L'utilisateur doit être authentifié pour rejoindre une discussion
    return $user !== null;
});

// Canal de présence pour une discussion (qui est en ligne)
Broadcast::channel('presence-discussion.{discussionId}', function ($user, $discussionId) {
    if ($user) {
        return ['id' => $user->id, 'nom' => $user->prenom . ' ' . $user->nom];
    }
});
