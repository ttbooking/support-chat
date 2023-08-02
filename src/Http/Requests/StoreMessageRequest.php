<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
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
     * @return array
     */
    public function rules(): array
    {
        return [
            'id' => 'required|nanoid|size:21',
            'parent_id' => 'sometimes|nullable|nanoid|size:21',
            'content' => 'required_without:attachments|string',
            'attachments' => 'required_without:content|array',
            'attachments.*.name' => 'required|string|max:255',
            'attachments.*.type' => 'nullable|string|max:255',
            'attachments.*.size' => 'required|integer|min:0',
        ];
    }
}
