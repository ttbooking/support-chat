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
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            '_id' => $this->getKey(),
            'content' => $this->content,
            'senderId' => $this->sender_id,
            'username' => 'System John',
            'system' => false,
            'saved' => true,
            'distributed' => true,
            'seen' => true,
            'deleted' => false,
            'failure' => false,
            'disableActions' => false,
            'disableReactions' => false,
            'files' => MessageFileResource::collection($this->files),
            'replyMessage' => new static($this->parent),
        ];
    }
}
