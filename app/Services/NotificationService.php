<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Utilisateur;

class NotificationService
{
    /**
     * Notify admins about new comment to moderate
     */
    public static function notifyAdminsNewComment($commentaire)
    {
        // Get all admins with moderation permission
        $admins = Utilisateur::whereHas('role', function ($query) {
            $query->whereJsonContains('permissions', 'moderer_contenu');
        })->get();

        foreach ($admins as $admin) {
            Notification::create([
                'type' => 'comment_moderation',
                'message' => 'Nouveau commentaire à modérer',
                'data' => [
                    'commentaire_id' => $commentaire->id,
                    'ressource_id' => $commentaire->ressource_id,
                    'auteur' => $commentaire->auteur->prenom . ' ' . $commentaire->auteur->nom,
                ],
                'action_url' => '/admin?tab=moderation',
                'utilisateur_id' => $admin->id,
            ]);
        }
    }

    /**
     * Notify discussion participants about new message
     */
    public static function notifyDiscussionParticipants($discussion, $message, $senderId)
    {
        // Get all users who have sent messages in this discussion (excluding the sender)
        $participantIds = \App\Models\DiscussionMessage::where('discussion_id', $discussion->id)
            ->where('utilisateur_id', '!=', $senderId)
            ->distinct()
            ->pluck('utilisateur_id')
            ->toArray();

        // Also include the discussion creator if not already included
        if (!in_array($discussion->createur_id, $participantIds) && $discussion->createur_id != $senderId) {
            $participantIds[] = $discussion->createur_id;
        }

        // Create notifications for all participants
        foreach ($participantIds as $userId) {
            Notification::create([
                'type' => 'discussion_message',
                'message' => 'Nouveau message dans "' . $discussion->titre . '"',
                'data' => [
                    'discussion_id' => $discussion->id,
                    'ressource_id' => $discussion->ressource_id,
                    'message_id' => $message->id,
                    'auteur' => $message->utilisateur->prenom . ' ' . $message->utilisateur->nom,
                ],
                'action_url' => '/ressources/' . $discussion->ressource_id . '?discussion=' . $discussion->id,
                'utilisateur_id' => $userId,
            ]);
        }
    }
}
