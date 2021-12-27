<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use TTBooking\SupportChat\Models\Message;

/**
 * @mixin Message
 */
class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request|null  $request
     * @return array
     */
    public function toArray($request = null): array
    {
        return [
            '_id' => $this->getKey(),
            'content' => $this->content,
            'senderId' => $this->sender_id,
            'username' => $this->sender->getPersonInfo()->name,
            'system' => false,
            'saved' => true,
            'distributed' => true,
            'seen' => true,
            'deleted' => false,
            'failure' => false,
            'disableActions' => false,
            'disableReactions' => false,
            'files' => MessageFileResource::collection($this->files),
            'replyMessage' => $this->parent ? new static($this->parent) : null,
        ];
    }
}
