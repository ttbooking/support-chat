<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read string|null $filter
 * @property-read bool|null $showSearch
 * @property-read bool|null $showAddRoom
 * @property-read bool|null $showSendIcon
 * @property-read bool|null $showFiles
 * @property-read bool|null $showAudio
 * @property-read int<32, 320>|null $audioBitRate
 * @property-read int<8000, 48000>|null $audioSampleRate
 * @property-read bool|null $showEmojis
 * @property-read bool|null $showReactionEmojis
 * @property-read bool|null $showNewMessagesDivider
 * @property-read bool|null $showFooter
 */
class ShowChatRequest extends FormRequest
{
    /**
     * Handle a passed validation attempt.
     */
    protected function passedValidation(): void
    {
        $this->replace(
            array_filter([
                'filter' => (string) $this->query('filter'),
                'showSearch' => (bool) $this->query('showSearch'),
                'showAddRoom' => (bool) $this->query('showAddRoom'),
                'showSendIcon' => (bool) $this->query('showSendIcon'),
                'showFiles' => (bool) $this->query('showFiles'),
                'showAudio' => (bool) $this->query('showAudio'),
                'audioBitRate' => (int) $this->query('audioBitRate'),
                'audioSampleRate' => (int) $this->query('audioSampleRate'),
                'showEmojis' => (bool) $this->query('showEmojis'),
                'showReactionEmojis' => (bool) $this->query('showReactionEmojis'),
                'showNewMessagesDivider' => (bool) $this->query('showNewMessagesDivider'),
                'showFooter' => (bool) $this->query('showFooter'),
            ], $this->has(...), ARRAY_FILTER_USE_KEY)
        );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'filter' => 'sometimes|required|string',
            'showSearch' => 'sometimes|required|boolean',
            'showAddRoom' => 'sometimes|required|boolean',
            'showSendIcon' => 'sometimes|required|boolean',
            'showFiles' => 'sometimes|required|boolean',
            'showAudio' => 'sometimes|required|boolean',
            'audioBitRate' => 'sometimes|required|integer|between:32,320',
            'audioSampleRate' => 'sometimes|required|integer|between:8000,48000',
            'showEmojis' => 'sometimes|required|boolean',
            'showReactionEmojis' => 'sometimes|required|boolean',
            'showNewMessagesDivider' => 'sometimes|required|boolean',
            'showFooter' => 'sometimes|required|boolean',
        ];
    }
}
