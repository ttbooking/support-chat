<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Auth;
use TTBooking\SupportChat\Contracts\Personifiable;
use TTBooking\SupportChat\Observers\MessageReactionObserver;

/**
 * @property int $id
 * @property int $message_id
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
     * @return BelongsTo<Message, self>
     */
    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class);
    }

    /**
     * @return BelongsTo<Model&Personifiable, self>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(config('support-chat.user_model'));
    }

    public function resolveRouteBindingQuery($query, $value, $field = null): Relation
    {
        return parent::resolveRouteBindingQuery($query, $value, $field)->where('user_id', Auth::id());
    }
}
