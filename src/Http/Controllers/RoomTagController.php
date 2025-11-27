<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Response;
use TTBooking\SupportChat\Models\RoomTag;

class RoomTagController
{
    /**
     * Display a listing of all the existing rooms' tags.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        return JsonResource::collection(
            RoomTag::query()
                ->when($search = $request->query('search'))
                ->whereLike('name', '%'.$search.'%')
                ->orderBy('name')
                ->cursorPaginate()
        );
    }

    /**
     * Remove the specified room tag from storage.
     */
    public function destroy(RoomTag $tag): \Illuminate\Http\Response
    {
        $tag->delete();

        return Response::noContent();
    }
}
