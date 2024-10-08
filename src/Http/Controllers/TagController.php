<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Http\Controllers;

use Illuminate\Http\Request;
use TTBooking\SupportChat\Models\RoomTag;

class TagController
{
    /**
     * Display a listing of all the existing rooms' tags.
     */
    public function index(Request $request)
    {
        return RoomTag::query()
            ->when($search = $request->query('search'))
            ->whereLike('tag', '%'.$search.'%')
            ->orderBy('tag')
            ->cursorPaginate();
    }
}
