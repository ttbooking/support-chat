<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Tags\Tag;
use TTBooking\SupportChat\Models\Room;

class TagController
{
    /**
     * Display a listing of all the existing rooms' tags.
     */
    public function index(Request $request)
    {
        $room = new Room;

        return Tag::query()
            ->whereMorphedTo($room->getTaggableMorphName(), Room::class)
            ->when($search = $request->query('search'))
            ->containing($search)
            ->ordered()
            ->cursorPaginate();
    }
}
