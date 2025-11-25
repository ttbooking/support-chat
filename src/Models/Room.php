<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User;
use TTBooking\Nanoid\Concerns\HasNanoids;
use TTBooking\SupportChat\Database\Factories\RoomFactory;
use TTBooking\SupportChat\Models\Scopes\ParticipantScope;
use TTBooking\SupportChat\Observers\RoomObserver;
use TTBooking\SupportChat\Policies\RoomPolicy;

/**
 * @property string $id
 * @property string $name
 * @property int $created_by
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 * @property User $creator
 * @property Collection<int, User> $users
 * @property Collection<int, RoomTag> $tags
 * @property Collection<int, Message> $messages
 * @property Message|null $lastMessage
 */
#[
    ObservedBy(RoomObserver::class),
    ScopedBy(ParticipantScope::class),
    UseFactory(RoomFactory::class),
    UsePolicy(RoomPolicy::class),
]
class Room extends Model
{
    /** @use HasFactory<RoomFactory> */
    use HasFactory, HasNanoids, SoftDeletes;

    protected $table = 'chat_rooms';

    protected int $nanoidSize = 7;

    protected $fillable = ['id', 'name', 'created_by'];

    protected $with = ['tags', 'lastMessage'];

    protected static function booted(): void
    {
        static::deleting(static function (self $room) {
            $room->isForceDeleting()
                ? $room->messages()->forceDelete()
                : $room->messages()->delete();
        });
    }

    public function nanoidSize(): int
    {
        return config('support-chat.nanoid_size_rooms') ?: $this->nanoidSize ?? 21;
    }

    /**
     * @return Attribute<string, never>
     */
    protected function name(): Attribute
    {
        return Attribute::get(
            fn (?string $name): string => $name ?? 'Room '.$this->id
        );
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function creator(): BelongsTo
    {
        /** @var class-string<User> $model */
        $model = config('support-chat.user_model');

        return $this->belongsTo($model, 'created_by');
    }

    /**
     * @return BelongsToMany<User, $this>
     */
    public function users(): BelongsToMany
    {
        /** @var class-string<User> $model */
        $model = config('support-chat.user_model');

        return $this->belongsToMany($model, 'chat_participants')
            ->using(Participant::class)
            ->as('participant')
            ->withTimestamps();
    }

    /**
     * @return BelongsToMany<RoomTag, $this>
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(RoomTag::class, 'chat_room_tag', relatedPivotKey: 'tag_name')->withTimestamps();
    }

    /**
     * @return HasMany<Message, $this>
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->orderBy('created_at')->withTrashed();
    }

    /**
     * @return HasOne<Message, $this>
     */
    public function lastMessage(): HasOne
    {
        return $this->hasOne(Message::class)->latestOfMany('created_at');
    }
}
