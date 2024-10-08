<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property string $tag
 * @property Carbon $created_at
 * @property Collection<int, Room> $rooms
 */
class RoomTag extends Model
{
    protected $primaryKey = 'tag';

    protected $keyType = 'string';

    public $incrementing = false;

    const UPDATED_AT = null;

    protected $fillable = ['tag'];

    /**
     * @return BelongsToMany<Room>
     */
    public function rooms(): BelongsToMany
    {
        return $this->belongsToMany(Room::class, 'room_tag', foreignPivotKey: 'tag')->withTimestamps();
    }
}
