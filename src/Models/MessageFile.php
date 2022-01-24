<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
 */
class MessageFile extends Model
{
    protected $touches = ['message'];

    protected $fillable = ['name', 'type', 'size'];

    public $timestamps = false;

    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class);
    }
}
