<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use TTBooking\SupportChat\Enums\MessageState;
use TTBooking\SupportChat\Models\Message;

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
        return [
            '_id' => $this->getKey(),
            'indexId' => $this->getKey(),
            'content' => ! $this->trashed() ? $this->content : '',
            'senderId' => (string) $this->sender_id,
            'username' => $this->sender->getPersonInfo()->name,
            'system' => (bool) ($this->flags & Message::FLAG_SYSTEM),
            'saved' => $this->exists,
            'distributed' => in_array($this->state, [MessageState::Distributed, MessageState::Seen], true),
            'seen' => $this->state === MessageState::Seen,
            'deleted' => $this->trashed(),
            'failure' => $this->state === MessageState::Failure,
            'disableActions' => (bool) ($this->flags & Message::FLAG_DISABLE_ACTIONS),
            'disableReactions' => (bool) ($this->flags & Message::FLAG_DISABLE_REACTIONS),
            'files' => ! $this->trashed() ? MessageFileResource::collection($this->files) : [],
            'reactions' => $this->reactionsWithUsers,
            'replyMessage' => $this->parent ? new self($this->parent) : null,
        ];
    }
}
