<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use TTBooking\SupportChat\SupportChat;

class ParticipantScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (! ($user = auth()->user()) || SupportChat::canViewForeignRooms($user)) {
            return;
        }

        $builder->whereHas('users', static function (Builder $query) {
            $query->whereKey(auth()->id());
        });
    }
}
