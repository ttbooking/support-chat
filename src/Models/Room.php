<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use TTBooking\Nanoid\Concerns\HasNanoids;
use TTBooking\SupportChat\Database\Factories\RoomFactory;
use TTBooking\SupportChat\Http\Resources\RoomResource;
use TTBooking\SupportChat\Models\Scopes\ParticipantScope;
use TTBooking\SupportChat\Observers\RoomObserver;
use TTBooking\SupportChat\Policies\RoomPolicy;
use TTBooking\SupportChat\Support\Tag;
use TTBooking\SupportChat\SupportChat;

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
 * @property Collection<int, Model> $subjects
 * @property Collection<int, Message> $messages
 * @property Message|null $lastMessage
 *
 * @method static Builder<$this> withDescriptor(string $descriptor, bool $strict = false)
 * @method static Builder<$this> withRoom(string $qualifier, bool $strict = false)
 * @method static Builder<$this> withUser(string $qualifier, bool $strict = false)
 * @method static Builder<$this> withTag(string|Model|Tag $qualifier, bool $strict = false)
 */
#[
    ObservedBy(RoomObserver::class),
    ScopedBy(ParticipantScope::class),
    UseFactory(RoomFactory::class),
    UsePolicy(RoomPolicy::class),
    UseResource(RoomResource::class),
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
        /** @var int */
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
        return $this->belongsTo(SupportChat::userModel(), 'created_by');
    }

    /**
     * @return BelongsToMany<User, $this>
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(SupportChat::userModel(), 'chat_participants')
            ->using(Participant::class)
            ->as('participant')
            ->withTimestamps();
    }

    /**
     * @return HasMany<RoomTag, $this>
     */
    public function tags(): HasMany
    {
        return $this->hasMany(RoomTag::class);
    }

    /**
     * @return HasManyThrough<Model, RoomTag, $this>
     */
    public function subjects(): HasManyThrough
    {
        return $this->through('tags')->has('subject');
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

    #[Scope]
    protected function withDescriptor(Builder $query, string $descriptor, bool $strict = false): void
    {
        $pattern = '/(?:(room|user|tag):)?(\".+?(?<!\\\)\"|\S+)/';
        preg_match_all($pattern, $descriptor, $matches, PREG_SET_ORDER | PREG_UNMATCHED_AS_NULL);

        foreach ($matches as [, $realm, $qualifier]) {
            $qualifier = strtr(Str::unwrap($qualifier, '"'), ['\\"' => '"']);
            match ($realm) {
                'user' => $this->withUser($query, $qualifier, $strict),
                'tag' => $this->withTag($query, $qualifier, $strict),
                default => $this->withRoom($query, $qualifier, $strict),
            };
        }
    }

    #[Scope]
    protected function withRoom(Builder $query, string $qualifier, bool $strict = false): void
    {
        $query->where(static fn (Builder $query) => $query->whereKey($qualifier)->when(
            $strict,
            static fn (Builder $query) => $query->orWhere('name', $qualifier),
            static fn (Builder $query) => $query->orWhereLike('name', $qualifier.'%'),
        ));
    }

    #[Scope]
    protected function withUser(Builder $query, string $qualifier, bool $strict = false): void
    {
        $nameKey = config('support-chat.user_name_key');
        $credKey = config('support-chat.user_cred_key');

        $query->whereHas('users', function (Builder $query) use ($qualifier, $strict, $nameKey, $credKey) {
            $query
                ->whereKey($qualifier)
                ->when(
                    $strict,
                    static fn (Builder $query) => $query->orWhere($nameKey, $qualifier),
                    static fn (Builder $query) => $query->orWhereLike($nameKey, $qualifier.'%'),
                )
                ->when(
                    $strict,
                    static fn (Builder $query) => $query->orWhere($credKey, $qualifier),
                    static fn (Builder $query) => $query->orWhereLike($credKey, $qualifier.'%'),
                );
        });
    }

    #[Scope]
    protected function withTag(Builder $query, string|Model|Tag $tag, bool $strict = false): void
    {
        $tag = Tag::from($tag);

        $query->whereHas('tags', function (Builder $query) use ($tag, $strict) {
            $query
                ->when($tag->type)
                ->where('type', $tag->type)
                ->when(
                    $strict,
                    static fn (Builder $query) => $query->where('name', $tag->name),
                    static fn (Builder $query) => $query->whereLike('name', $tag->name.'%'),
                );
        });
    }
}
