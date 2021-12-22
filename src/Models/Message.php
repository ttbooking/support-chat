<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use TTBooking\SupportChat\Contracts\Personifiable;

/**
 * @property int $id
 * @property int $room_id
 * @property int $sender_id
 * @property int|null $parent_id
 * @property int $type
 * @property string $content
 * @property int $state
 * @property int $flags
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 * @property Room $room
 * @property Model|Personifiable $sender
 * @property Message|null $parent
 * @property Collection|Message[] $replies
 * @property Collection|MessageFile[] $files
 * @property Collection|Model[]|Personifiable[] $reactedUsers
 */
class Message extends Model
{
    use SoftDeletes;

    protected $touches = ['room'];

    protected $fillable = ['sender_id', 'parent_id', 'content'];

    protected static function booted(): void
    {
        static::deleting(function (self $message) {
            if ($message->isForceDeleting()) {
                $message->replies()->forceDelete();
                $message->files()->forceDelete();
            }
        });
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(config('support-chat.user_model'));
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(static::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(static::class, 'parent_id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(MessageFile::class);
    }

    public function reactedUsers(): BelongsToMany
    {
        return $this->belongsToMany(config('support-chat.user_model'), 'message_reactions');
    }
}
