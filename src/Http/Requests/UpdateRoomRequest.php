<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use TTBooking\SupportChat\Models\Room;
use TTBooking\SupportChat\Models\RoomTag;
use TTBooking\SupportChat\SupportChat;

/**
 * @property-read Room $room
 */
class UpdateRoomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->room) ?? false;
    }

    /**
     * Prepare the data for validation.
     */
    public function prepareForValidation(): void
    {
        $this->merge([
            'name' => $this->roomName,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        $model = SupportChat::userModel();

        return [
            'id' => 'sometimes|nanoid|size:'.(new Room)->nanoidSize(),
            'name' => 'sometimes|nullable|string|max:255',
            'users' => 'sometimes|array',
            'users.*._id' => "sometimes|exists:$model,id",
            'tags' => 'sometimes|array',
            'tags.*.name' => 'sometimes|exists:'.RoomTag::class.',name', // TODO: !!!
            'tags.*.type' => 'sometimes|nullable|string|max:32',
        ];
    }
}
