<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Models;

use ArrayObject;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User;
use TTBooking\Nanoid\Concerns\HasNanoids;
use TTBooking\SupportChat\Database\Factories\MessageFactory;
use TTBooking\SupportChat\Enums\MessageState;
use TTBooking\SupportChat\Observers\MessageObserver;

/**
 * @property string $id
 * @property string $room_id
 * @property int $sent_by
 * @property string|null $reply_to
 * @property string $content
 * @property array|null $meta
 * @property MessageState $state
 * @property int $flags
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 * @property Room $room
 * @property User $sender
 * @property Message|null $origin
 * @property Collection<int, Message> $replies
 * @property Collection<int, Attachment> $attachments
 * @property Collection<int, Attachment> $files
 * @property Collection<int, Reaction> $reactions
 * @property-read string $attachmentPath
 * @property-read ArrayObject<string, int[]> $reactionsWithUsers
 */
#[ObservedBy(MessageObserver::class)]
class Message extends Model
{
    /** @use HasFactory<MessageFactory> */
    use HasFactory, HasNanoids, SoftDeletes;

    protected $table = 'chat_messages';

    protected int $nanoidSize = 7;

    /** @var list<string> */
    protected $touches = ['room'];

    protected $fillable = ['id', 'sent_by', 'reply_to', 'content', 'meta'];

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
    }

    protected static function newFactory(): MessageFactory
    {
        return MessageFactory::new();
    }

    /**
     * @return BelongsTo<Room, $this>
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function sender(): BelongsTo
    {
        /** @var class-string<User> $model */
        $model = config('support-chat.user_model');

        return $this->belongsTo($model, 'sent_by');
    }

    /**
     * @return BelongsTo<self, $this>
     */
    public function origin(): BelongsTo
    {
        return $this->belongsTo(static::class, 'reply_to');
    }

    /**
     * @return HasMany<self, $this>
     */
    public function replies(): HasMany
    {
        return $this->hasMany(static::class, 'reply_to');
    }

    /**
     * @return HasMany<Attachment, $this>
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class);
    }

    /**
     * @return HasMany<Attachment, $this>
     */
    public function files(): HasMany
    {
        return $this->hasMany(Attachment::class);
    }

    /**
     * @return HasMany<Reaction, $this>
     */
    public function reactions(): HasMany
    {
        return $this->hasMany(Reaction::class);
    }

    /**
     * @return Attribute<string, never>
     */
    protected function attachmentPath(): Attribute
    {
        return Attribute::get(
            fn () => 'attachments/'.$this->room_id.'/'.$this->getKey()
        );
    }

    /**
     * @return Attribute<ArrayObject<string, int[]>, never>
     */
    protected function reactionsWithUsers(): Attribute
    {
        return Attribute::get(
            fn () => new ArrayObject($this->reactions->mapToGroups(
                static fn (Reaction $reaction) => [$reaction->emoji => $reaction->user_id]
            )->toArray())
        );
    }

    public function getAttachment(string $filename): Attachment
    {
        return $this->files->where('name', $filename)->first();
    }
}
