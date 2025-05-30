<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use TTBooking\SupportChat\Models\Message;
use TTBooking\SupportChat\Models\Room;

/**
 * @property-read Room $room
 */
class StoreMessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', [Message::class, $this->room]);
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if (empty($this['content'])) {
            unset($this['content']);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'id' => 'required|nanoid|size:7',
            'reply_to' => 'sometimes|nullable|nanoid|size:7',
            'content' => 'required_without:attachments|string',
            'attachments' => 'required_without:content|array',
            'attachments.*.name' => 'required|string|max:255',
            'attachments.*.type' => 'nullable|string|max:255',
            'attachments.*.size' => 'required|integer|min:0',
        ];
    }
}
