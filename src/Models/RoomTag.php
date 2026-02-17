<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use TTBooking\SupportChat\Database\Factories\RoomTagFactory;
use TTBooking\SupportChat\Http\Resources\RoomTagResource;
use TTBooking\SupportChat\Support\Tag;

/**
 * @property int $id
 * @property string $room_id
 * @property string $name
 * @property string $type
 * @property Carbon $created_at
 * @property Tag $tag
 * @property Model|null $subject
 * @property Room $room
 *
 * @method static Builder whereTag(string|Model|Tag $tag)
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
    protected $touches = ['room'];

    protected $fillable = ['name', 'type', 'tag'];

    /**
     * @return Attribute<Tag, string|Model|Tag>
     */
    protected function tag(): Attribute
    {
        return Attribute::make(
            get: static fn (mixed $value, array $attributes) => new Tag($attributes['name'], $attributes['type']),
            set: static fn (string|Model|Tag $value) => Tag::from($value)->toArray()
        )->withoutObjectCaching();
    }

    #[Scope]
    protected function whereTag(Builder $query, string|Model|Tag $tag): void
    {
        $query->where(Tag::from($tag)->toArray());
    }

    /**
     * @return MorphTo<Model, $this>
     */
    public function subject(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'type', 'name');
    }

    /**
     * @return BelongsTo<Room, $this>
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}
