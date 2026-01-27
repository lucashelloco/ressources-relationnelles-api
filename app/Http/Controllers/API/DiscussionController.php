<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Events\DiscussionMessageSent;
use App\Models\Discussion;
use App\Models\DiscussionMessage;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DiscussionController extends Controller
{
    /**
     * Get discussions for a resource
     */
    public function index(Request $request, $ressourceId)
    {
        $discussions = Discussion::where('ressource_id', $ressourceId)
            ->where('est_actif', true)
            ->with(['createur', 'dernierMessage.utilisateur'])
            ->withCount('messages')
            ->orderBy('derniere_activite', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $discussions
        ]);
    }

    /**
     * Create a new discussion
     */
    public function store(Request $request, $ressourceId)
    {
        $validator = Validator::make($request->all(), [
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $discussion = Discussion::create([
            'titre' => $request->titre,
            'description' => $request->description,
            'ressource_id' => $ressourceId,
            'createur_id' => $request->user()->id,
            'derniere_activite' => now(),
        ]);

        $discussion->load('createur');

        return response()->json([
            'success' => true,
            'message' => 'Discussion créée avec succès',
            'data' => $discussion
        ], 201);
    }

    /**
     * Get messages for a discussion
     */
    public function getMessages($ressourceId, $discussionId)
    {
        $discussion = Discussion::where('ressource_id', $ressourceId)
            ->where('id', $discussionId)
            ->with(['messages.utilisateur', 'createur'])
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $discussion
        ]);
    }

    /**
     * Send a message to a discussion
     */
    public function sendMessage(Request $request, $ressourceId, $discussionId)
    {
        $validator = Validator::make($request->all(), [
            'contenu' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $discussion = Discussion::where('ressource_id', $ressourceId)
            ->where('id', $discussionId)
            ->firstOrFail();

        $message = DiscussionMessage::create([
            'contenu' => $request->contenu,
            'discussion_id' => $discussionId,
            'utilisateur_id' => $request->user()->id,
        ]);

        // Update discussion last activity
        $discussion->update([
            'derniere_activite' => now(),
        ]);

        $message->load('utilisateur');

        // Broadcast the message to all listeners
        broadcast(new DiscussionMessageSent($message))->toOthers();

        // Notify all discussion participants
        NotificationService::notifyDiscussionParticipants($discussion, $message, $request->user()->id);

        return response()->json([
            'success' => true,
            'message' => 'Message envoyé avec succès',
            'data' => $message
        ], 201);
    }

    /**
     * Delete a discussion (creator or admin only)
     */
    public function destroy(Request $request, $ressourceId, $discussionId)
    {
        $discussion = Discussion::where('ressource_id', $ressourceId)
            ->where('id', $discussionId)
            ->firstOrFail();

        // Check if user is creator or admin
        if ($discussion->createur_id !== $request->user()->id &&
            !$request->user()->hasPermission('moderer_contenu')) {
            return response()->json([
                'success' => false,
                'message' => 'Non autorisé'
            ], 403);
        }

        $discussion->delete();

        return response()->json([
            'success' => true,
            'message' => 'Discussion supprimée avec succès'
        ]);
    }

    /**
     * Get user's discussions (discussions they participated in)
     */
    public function getUserDiscussions(Request $request)
    {
        $userId = $request->user()->id;

        // Get discussions where user has sent at least one message or created the discussion
        $discussions = Discussion::where(function($query) use ($userId) {
            $query->where('createur_id', $userId)
                  ->orWhereHas('messages', function($q) use ($userId) {
                      $q->where('utilisateur_id', $userId);
                  });
        })
        ->where('est_actif', true)
        ->with(['createur', 'ressource'])
        ->withCount('messages')
        ->orderBy('derniere_activite', 'desc')
        ->get();

        return response()->json([
            'success' => true,
            'data' => $discussions
        ]);
    }
}
