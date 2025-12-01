<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Models;

use Carbon\Carbon;
use Illuminate\Database\ClassMorphViolationException;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use TTBooking\SupportChat\Database\Factories\RoomTagFactory;
use TTBooking\SupportChat\Http\Resources\RoomTagResource;

/**
 * @property int $id
 * @property string $name
 * @property string $type
 * @property Carbon $created_at
 * @property string $link
 * @property Model|null $subject
 * @property Collection<int, Room> $rooms
 */
#[
    UseFactory(RoomTagFactory::class),
    UseResource(RoomTagResource::class),
]
class RoomTag extends Model
{
    /** @use HasFactory<RoomTagFactory> */
    use HasFactory;

    protected $table = 'chat_room_tags';

    const UPDATED_AT = null;

    /** @var list<string> */
    protected $touches = ['rooms'];

    protected $fillable = ['name', 'type', 'link'];

    /**
     * @return Attribute<string, string|Model>
     */
    protected function link(): Attribute
    {
        return Attribute::make(
            get: static function (mixed $value, array $attributes) {
                return ltrim($attributes['type'].':'.$attributes['name'], ':');
            },
            set: static function (string|Model $value) {
                if (is_string($value)) {
                    [$type, $name] = str_contains($value, ':') ? explode(':', $value, 2) : ['', $value];

                    return compact('type', 'name');
                }

                if ($value::class === $alias = $value->getMorphClass()) {
                    throw new ClassMorphViolationException($value);
                }

                return [
                    'type' => $alias,
                    'name' => $value->getKey(),
                ];
            }
        );
    }

    /**
     * @return MorphTo<Model, $this>
     */
    public function subject(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'type', 'name');
    }

    /**
     * @return BelongsToMany<Room, $this>
     */
    public function rooms(): BelongsToMany
    {
        return $this->belongsToMany(Room::class, 'chat_room_tag', foreignPivotKey: 'tag_id')->withTimestamps();
    }
}
