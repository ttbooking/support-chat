<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property int $message_id
 * @property string $name
 * @property int $size
 * @property string $type
 * @property bool $audio
 * @property float $duration
 * @property string $url
 * @property string $preview
 * @property Message $message
 * @property string $attachmentPath
 */
class MessageFile extends Model
{
    protected $casts = [
        'audio' => 'bool',
    ];

    protected $touches = ['message'];

    protected $fillable = ['name', 'type', 'size'];

    public $timestamps = false;

    protected static function booted(): void
    {
        static::deleted(function (self $attachment) {
            Storage::delete($attachment->attachmentPath);
        });
    }

    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class);
    }

    public function attachmentPath(): Attribute
    {
        return new Attribute(
            get: fn () => $this->message->attachmentPath.'/'.$this->name
        );
    }
}
