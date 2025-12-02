<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use TTBooking\SupportChat\Http\Requests\StoreRoomRequest;
use TTBooking\SupportChat\Http\Requests\UpdateRoomRequest;
use TTBooking\SupportChat\Http\Resources\RoomResource;
use TTBooking\SupportChat\Models\Room;
use TTBooking\SupportChat\Support\Tag;

class RoomController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(Room::class);
    }

    /**
     * Display a listing of the rooms.
     */
    public function index(): ResourceCollection
    {
        return Room::all()->toResourceCollection();
    }

    /**
     * Store a newly created room in storage.
     */
    public function store(StoreRoomRequest $request): RoomResource
    {
        $room = Room::query()->create($request->safe()->except('users', 'tags') + ['created_by' => auth()->id()]);
        $room->users()->sync($request->validated('users.*._id'));

        foreach ($request->validated('tags.*') as $tag) {
            $room->tags()->createOrFirst(Tag::from($tag)->toArray());
        }

        return $room->toResource();
    }

    /**
     * Display the specified room.
     */
    public function show(Room $room): RoomResource
    {
        /** @var RoomResource */
        return $room->toResource();
    }

    /**
     * Update the specified room in storage.
     */
    public function update(UpdateRoomRequest $request, Room $room): RoomResource
    {
        $room->update($request->safe()->except('users', 'tags'));
        $room->users()->sync($request->validated('users.*._id'));

        foreach ($request->validated('tags.*') as $tag) {
            $room->tags()->createOrFirst(Tag::from($tag)->toArray());
        }

        /** @var RoomResource */
        return $room->toResource();
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
