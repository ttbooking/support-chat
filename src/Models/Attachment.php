<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property string $message_id
 * @property string $name
 * @property int $size
 * @property string $type
 * @property bool $audio
 * @property float|null $duration
 * @property string|null $url
 * @property string|null $preview
 * @property Message $message
 * @property-read string $attachmentPath
 */
class Attachment extends Model
{
    protected $table = 'chat_attachments';

    protected $casts = [
        'audio' => 'bool',
    ];

    /** @var list<string> */
    protected $touches = ['message'];

    protected $fillable = ['name', 'type', 'size'];

    public $timestamps = false;

    protected static function booted(): void
    {
        static::deleted(static function (self $attachment) {
            Storage::disk(config('support-chat.disk'))->delete($attachment->attachmentPath);
        });
    }

    /**
     * @return BelongsTo<Message, $this>
     */
    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class);
    }

    /**
     * @return Attribute<string, never>
     */
    protected function attachmentPath(): Attribute
    {
        return Attribute::get(
            fn () => $this->message->attachmentPath.'/'.$this->name
        );
    }
}
