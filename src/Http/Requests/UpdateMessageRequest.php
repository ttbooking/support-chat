<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use TTBooking\SupportChat\Models\Message;

/**
 * @property-read Message $message
 */
class UpdateMessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->message) ?? false;
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
            'id' => 'required|nanoid|size:'.(new Message)->nanoidSize(),
            'reply_to' => 'sometimes|nullable|nanoid|size:'.(new Message)->nanoidSize(),
            'content' => 'required_without:attachments|string',
            'attachments' => 'required_without:content|array',
            'attachments.*.name' => 'required|string|max:255',
            'attachments.*.type' => 'nullable|string|max:255',
            'attachments.*.size' => 'required|integer|min:0',
        ];
    }
}
