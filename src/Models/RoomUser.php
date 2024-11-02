<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use TTBooking\SupportChat\Contracts\Personifiable;

/**
 * @property string $room_id
 * @property int $user_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Room $room
 * @property Model $user
 */
class RoomUser extends Pivot
{
    /** @var list<string> */
    protected $touches = ['room'];

    /**
     * @return BelongsTo<Room, $this>
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * @return BelongsTo<Model, $this>
     */
    public function user(): BelongsTo
    {
        /** @var class-string<Model&Personifiable> $model */
        $model = config('support-chat.user_model');

        return $this->belongsTo($model);
    }
}
