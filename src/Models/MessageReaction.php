<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use TTBooking\SupportChat\Contracts\Personifiable;
use TTBooking\SupportChat\Observers\MessageReactionObserver;

/**
 * @property int $id
 * @property string $message_id
 * @property int $user_id
 * @property string $emoji
 * @property Carbon $created_at
 * @property Message $message
 * @property Model&Personifiable $user
 */
class MessageReaction extends Model
{
    protected $fillable = ['user_id', 'emoji'];

    const UPDATED_AT = null;

    protected static function booted(): void
    {
        static::observe(MessageReactionObserver::class);
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'emoji';
    }

    /**
     * @return BelongsTo<Message, $this>
     */
    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class);
    }

    /**
     * @return BelongsTo<Model&Personifiable, $this>
     */
    public function user(): BelongsTo
    {
        /** @var class-string<Model&Personifiable> $model */
        $model = config('support-chat.user_model');

        return $this->belongsTo($model);
    }

    public function resolveRouteBindingQuery($query, $value, $field = null)
    {
        return parent::resolveRouteBindingQuery($query, $value, $field)->where('user_id', Auth::id());
    }
}
