<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Http\Controllers;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use TTBooking\SupportChat\Http\Requests\StoreRoomRequest;
use TTBooking\SupportChat\Http\Resources\RoomResource;
use TTBooking\SupportChat\Models\Room;

class RoomController extends Controller
{
    /**
     * Display a listing of the rooms.
     */
    public function index(): AnonymousResourceCollection
    {
        return RoomResource::collection(Room::all());
    }

    /**
     * Store a newly created room in storage.
     */
    public function store(StoreRoomRequest $request): RoomResource
    {
        $room = Room::query()->create($request->safe()->except('users', 'tags'));
        $room->users()->sync($request->validated('users.*._id'));
        $room->tags()->sync($request->validated('tags.*.name'));

        return new RoomResource($room);
    }

    /**
     * Display the specified room.
     */
    public function show(Room $room): RoomResource
    {
        return new RoomResource($room);
    }

    /**
     * Update the specified room in storage.
     */
    public function update(StoreRoomRequest $request, Room $room): RoomResource
    {
        $room->update($request->safe()->except('users', 'tags'));
        $room->users()->sync($request->validated('users.*._id'));
        $room->tags()->sync($request->validated('tags.*.name'));

        return new RoomResource($room);
    }

    /**
     * Remove the specified room from storage.
     */
    public function destroy(Room $room): \Illuminate\Http\Response
    {
        $room->delete();

        return Response::noContent();
    }
}
