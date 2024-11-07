<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use TTBooking\SupportChat\Models\Attachment;

/**
 * @mixin Attachment
 */
class AttachmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(?Request $request = null): array
    {
        return [
            'name' => pathinfo($this->name, PATHINFO_FILENAME),
            'type' => pathinfo($this->name, PATHINFO_EXTENSION),
            'size' => $this->size,
            'audio' => $this->audio,
            'duration' => $this->duration,
            'url' => route('support-chat.messages.attachments.show', [
                'message' => $this->message_id,
                'attachment' => $this->name,
            ]),
            'preview' => $this->preview ? 'data:image/png;base64,'.base64_encode($this->preview) : null,
            'progress' => Storage::disk(config('support-chat.disk'))->exists($this->attachmentPath) ? -1 : 0,
        ];
    }
}
