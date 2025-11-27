<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use TTBooking\SupportChat\Enums\MessageState;
use TTBooking\SupportChat\Models\Message;
use TTBooking\SupportChat\SupportChat;

/**
 * @mixin Message
 */
class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(?Request $request = null): array
    {
        $sender = $this->sender->toResource(SupportChat::userResource())->resolve($request);

        return [
            '_id' => $this->getKey(),
            'roomId' => $this->room_id,
            'senderId' => (string) $this->sent_by,
            'indexId' => $this->getKey(),
            'content' => ! $this->trashed() ? $this->content : '',
            'username' => $sender['username'],
            'avatar' => $sender['avatar'] ?? null,
            'date' => $this->created_at->translatedFormat('jS F'),
            'timestamp' => $this->created_at->translatedFormat('G:i'),
            'system' => (bool) ($this->flags & Message::FLAG_SYSTEM),
            'saved' => $this->exists,
            'distributed' => in_array($this->state, [MessageState::Distributed, MessageState::Seen], true),
            'seen' => $this->state === MessageState::Seen,
            'deleted' => $this->trashed(),
            'edited' => $this->updated_at > $this->created_at,
            'failure' => $this->state === MessageState::Failure,
            'disableActions' => (bool) ($this->flags & Message::FLAG_DISABLE_ACTIONS),
            'disableReactions' => (bool) ($this->flags & Message::FLAG_DISABLE_REACTIONS),
            'files' => ! $this->trashed() ? $this->files->toResourceCollection() : [],
            'reactions' => $this->reactionsWithUsers,
            'replyMessage' => $this->origin ? new self($this->origin) : null,
        ];
    }
}
