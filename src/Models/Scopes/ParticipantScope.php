<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ParticipantScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (auth()->hasUser()) {
            $builder->whereHas('users', function (Builder $query) {
                $query->whereKey(auth()->id());
            });
        }
    }
}
