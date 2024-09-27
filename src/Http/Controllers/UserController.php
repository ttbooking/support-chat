<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\ResourceCollection;
use TTBooking\SupportChat\Http\Resources\UserResource;

class UserController
{
    /**
     * Display a listing of the users.
     */
    public function index(): ResourceCollection
    {
        /** @var class-string<Model> $model */
        $model = config('support-chat.user_model');

        return UserResource::collection($model::query()->orderBy('name')->orderBy('id')->cursorPaginate());
    }

    /**
     * Display the specified user.
     */
    public function show(int $user): UserResource
    {
        /** @var class-string<Model> $model */
        $model = config('support-chat.user_model');

        return new UserResource($model::query()->findOrFail($user));
    }
}