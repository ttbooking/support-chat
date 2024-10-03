<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Models;

use ArrayObject;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use TTBooking\Nanoid\Concerns\HasNanoids;
use TTBooking\SupportChat\Contracts\Personifiable;
use TTBooking\SupportChat\Enums\MessageState;
use TTBooking\SupportChat\Observers\MessageObserver;

/**
 * @property string $id
 * @property string $room_id
 * @property int $sender_id
 * @property string|null $parent_id
 * @property int $type
 * @property string $content
 * @property array|null $meta
 * @property MessageState $state
 * @property int $flags
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 * @property Room $room
 * @property Model&Personifiable $sender
 * @property Message|null $parent
 * @property Collection<int, Message> $replies
 * @property Collection<int, MessageFile> $attachments
 * @property Collection<int, MessageFile> $files
 * @property Collection<int, MessageReaction> $reactions
 * @property-read string $attachmentPath
 * @property-read ArrayObject<string, int[]> $reactionsWithUsers
 */
class Message extends Model
{
    use HasNanoids, SoftDeletes;

    protected int $nanoidSize = 7;

    /** @var list<string> */
    protected $touches = ['room'];

    protected $fillable = ['id', 'sender_id', 'parent_id', 'content', 'meta'];

    /** @var array<string, mixed> */
    protected $attributes = [
        'content' => '',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'meta' => 'array',
        'state' => MessageState::class,
    ];

    protected $dateFormat = 'Y-m-d H:i:s.u';

    public const FLAG_SYSTEM = 0b0001;

    public const FLAG_DISABLE_ACTIONS = 0b0010;

    public const FLAG_DISABLE_REACTIONS = 0b0100;

    protected static function booted(): void
    {
        static::deleting(static function (self $message) {
            $message->attachments->each->delete();
            if ($message->isForceDeleting()) {
                $message->reactions()->delete();
                $message->replies()->forceDelete();
            }
        });

        static::observe(MessageObserver::class);
    }

    /**
     * @return BelongsTo<Room, self>
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * @return BelongsTo<Model&Personifiable, self>
     */
    public function sender(): BelongsTo
    {
        /** @var class-string<Model> $model */
        $model = config('support-chat.user_model');

        return $this->belongsTo($model);
    }

    /**
     * @return BelongsTo<self, self>
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(static::class);
    }

    /**
     * @return HasMany<self>
     */
    public function replies(): HasMany
    {
        return $this->hasMany(static::class, 'parent_id');
    }

    /**
     * @return HasMany<MessageFile>
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(MessageFile::class);
    }

    /**
     * @return HasMany<MessageFile>
     */
    public function files(): HasMany
    {
        return $this->hasMany(MessageFile::class);
    }

    /**
     * @return HasMany<MessageReaction>
     */
    public function reactions(): HasMany
    {
        return $this->hasMany(MessageReaction::class);
    }

    /**
     * @return Attribute<string, never>
     */
    protected function attachmentPath(): Attribute
    {
        return Attribute::get(
            fn () => 'support-chat/room/'.$this->room_id.'/'.$this->getKey()
        );
    }

    /**
     * @return Attribute<ArrayObject<string, int[]>, never>
     */
    protected function reactionsWithUsers(): Attribute
    {
        return Attribute::get(
            fn () => new ArrayObject($this->reactions->mapToGroups(
                static fn (MessageReaction $reaction) => [$reaction->emoji => $reaction->user_id]
            )->toArray())
        );
    }

    public function getAttachment(string $filename): MessageFile
    {
        return $this->files->where('name', $filename)->first();
    }
}
