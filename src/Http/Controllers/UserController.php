<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use TTBooking\SupportChat\SupportChat;

class UserController
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request): ResourceCollection
    {
        return SupportChat::userModel()::query()
            ->when($search = $request->query('search'))
            ->whereLike('name', '%'.$search.'%')
            ->orderBy('name')->orderBy('id')
            ->cursorPaginate()
            ->toResourceCollection(SupportChat::userResource());
    }

    /**
     * Display the specified user.
     */
    public function show(int $user): JsonResource
    {
        return SupportChat::userModel()::query()->findOrFail($user)->toResource(SupportChat::userResource());
    }
}
