<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Foundation\Auth\User;
use TTBooking\SupportChat\Observers\ParticipantObserver;

/**
 * @property string $room_id
 * @property int $user_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Room $room
 * @property User $user
 */
#[ObservedBy(ParticipantObserver::class)]
class Participant extends Pivot
{
    protected $table = 'chat_participants';

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
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        /** @var class-string<User> $model */
        $model = config('support-chat.user_model');

        return $this->belongsTo($model);
    }
}
