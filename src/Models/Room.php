<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use TTBooking\Nanoid\Concerns\HasNanoids;
use TTBooking\SupportChat\Contracts\Personifiable;
use TTBooking\SupportChat\Database\Factories\RoomFactory;
use TTBooking\SupportChat\Observers\RoomObserver;

/**
 * @property string $id
 * @property string $name
 * @property int $created_by
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 * @property Model&Authenticatable $creator
 * @property Collection<int, Model&Personifiable> $users
 * @property Collection<int, RoomTag> $tags
 * @property Collection<int, Message> $messages
 */
#[ObservedBy(RoomObserver::class)]
class Room extends Model
{
    /** @use HasFactory<RoomFactory> */
    use HasFactory, HasNanoids, SoftDeletes;

    protected $table = 'chat_rooms';

    protected int $nanoidSize = 7;

    protected $fillable = ['id', 'name', 'created_by'];

    protected $with = ['tags'];

    protected static function booted(): void
    {
        static::deleting(static function (self $room) {
            $room->isForceDeleting()
                ? $room->messages()->forceDelete()
                : $room->messages()->delete();
        });
    }

    protected static function newFactory(): RoomFactory
    {
        return RoomFactory::new();
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
     * @return BelongsTo<Model&Authenticatable, $this>
     */
    public function creator(): BelongsTo
    {
        /** @var class-string<Model&Authenticatable> $model */
        $model = config('support-chat.user_model');

        return $this->belongsTo($model, 'created_by');
    }

    /**
     * @return BelongsToMany<Model&Personifiable, $this>
     */
    public function users(): BelongsToMany
    {
        /** @var class-string<Model&Personifiable> $model */
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
}
