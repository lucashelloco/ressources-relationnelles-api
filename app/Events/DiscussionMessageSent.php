<?php

namespace App\Events;

use App\Models\DiscussionMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DiscussionMessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct(DiscussionMessage $message)
    {
        $this->message = $message->load('utilisateur');
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('discussion.' . $this->message->discussion_id),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,
            'contenu' => $this->message->contenu,
            'utilisateur' => [
                'id' => $this->message->utilisateur->id,
                'nom' => $this->message->utilisateur->nom,
                'prenom' => $this->message->utilisateur->prenom,
            ],
            'created_at' => $this->message->created_at->toISOString(),
        ];
    }
}
