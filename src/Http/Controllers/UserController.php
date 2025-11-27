<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use TTBooking\SupportChat\Http\Resources\UserResource;

class UserController
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request): ResourceCollection
    {
        /** @var class-string<Model> $model */
        $model = config('support-chat.user_model');

        return $model::query()
            ->when($search = $request->query('search'))
            ->whereLike('name', '%'.$search.'%')
            ->orderBy('name')->orderBy('id')
            ->cursorPaginate()
            ->toResourceCollection(UserResource::class);
    }

    /**
     * Display the specified user.
     */
    public function show(int $user): UserResource
    {
        /** @var class-string<Model> $model */
        $model = config('support-chat.user_model');

        return $model::query()->findOrFail($user)->toResource(UserResource::class);
    }
}
