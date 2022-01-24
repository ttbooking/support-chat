<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use TTBooking\SupportChat\Models\MessageFile;

/**
 * @mixin MessageFile
 */
class MessageFileResource extends JsonResource
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
            'name' => pathinfo($this->name, PATHINFO_FILENAME),
            'type' => pathinfo($this->name, PATHINFO_EXTENSION),
            'size' => $this->size,
            'audio' => $this->audio,
            'duration' => $this->duration,
            'url' => $this->url,
            'preview' => $this->preview ? 'data:image/png;base64,'.base64_encode($this->preview) : null,
            'progress' => 0,
        ];
    }
}
