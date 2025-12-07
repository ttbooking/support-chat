<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read bool|null $show_search
 * @property-read bool|null $show_add_room
 * @property-read bool|null $show_send_icon
 * @property-read bool|null $show_files
 * @property-read bool|null $show_audio
 * @property-read int<32, 320>|null $audio_bit_rate
 * @property-read int<8000, 48000>|null $audio_sample_rate
 * @property-read bool|null $show_emojis
 * @property-read bool|null $show_reaction_emojis
 * @property-read bool|null $show_new_messages_divider
 * @property-read bool|null $show_footer
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
                'show_search' => (bool) $this->query('show_search'),
                'show_add_room' => (bool) $this->query('show_add_room'),
                'show_send_icon' => (bool) $this->query('show_send_icon'),
                'show_files' => (bool) $this->query('show_files'),
                'show_audio' => (bool) $this->query('show_audio'),
                'audio_bit_rate' => (int) $this->query('audio_bit_rate'),
                'audio_sample_rate' => (int) $this->query('audio_sample_rate'),
                'show_emojis' => (bool) $this->query('show_emojis'),
                'show_reaction_emojis' => (bool) $this->query('show_reaction_emojis'),
                'show_new_messages_divider' => (bool) $this->query('show_new_messages_divider'),
                'show_footer' => (bool) $this->query('show_footer'),
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
            'show_search' => 'sometimes|required|boolean',
            'show_add_room' => 'sometimes|required|boolean',
            'show_send_icon' => 'sometimes|required|boolean',
            'show_files' => 'sometimes|required|boolean',
            'show_audio' => 'sometimes|required|boolean',
            'audio_bit_rate' => 'sometimes|required|integer|between:32,320',
            'audio_sample_rate' => 'sometimes|required|integer|between:8000,48000',
            'show_emojis' => 'sometimes|required|boolean',
            'show_reaction_emojis' => 'sometimes|required|boolean',
            'show_new_messages_divider' => 'sometimes|required|boolean',
            'show_footer' => 'sometimes|required|boolean',
        ];
    }
}
