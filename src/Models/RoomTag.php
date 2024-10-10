<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use TTBooking\SupportChat\Database\Factories\RoomTagFactory;

/**
 * @property string $tag
 * @property Carbon $created_at
 * @property Collection<int, Room> $rooms
 */
class RoomTag extends Model
{
    /** @use HasFactory<RoomTagFactory> */
    use HasFactory;

    protected $primaryKey = 'tag';

    protected $keyType = 'string';

    public $incrementing = false;

    const UPDATED_AT = null;

    /** @var list<string> */
    protected $touches = ['rooms'];

    protected $fillable = ['tag'];

    protected static function newFactory(): RoomTagFactory
    {
        return RoomTagFactory::new();
    }

    /**
     * @return BelongsToMany<Room>
     */
    public function rooms(): BelongsToMany
    {
        return $this->belongsToMany(Room::class, 'room_tag', foreignPivotKey: 'tag')->withTimestamps(parent::CREATED_AT, parent::UPDATED_AT);
    }
}
