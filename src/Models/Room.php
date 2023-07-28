<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use TTBooking\SupportChat\Contracts\Personifiable;

/**
 * @property int $id
 * @property string|null $subject_type
 * @property int|null $subject_id
 * @property string $name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 * @property Model|null $subject
 * @property Collection<int, Model&Personifiable> $users
 * @property Collection<int, Message> $messages
 */
class Room extends Model
{
    use SoftDeletes;

    protected $fillable = ['name'];

    protected static function booted(): void
    {
        static::deleting(static function (self $room) {
            $room->isForceDeleting()
                ? $room->messages()->forceDelete()
                : $room->messages()->delete();
        });
    }

    /**
     * @return Attribute<string, never>
     */
    protected function name(): Attribute
    {
        return Attribute::get(
            fn (?string $name): string => $name ?? 'Room '.$this->getKey()
        );
    }

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return BelongsToMany<Model&Personifiable>
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(config('support-chat.user_model'));
    }

    /**
     * @return HasMany<Message>
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->withTrashed();
    }
}
