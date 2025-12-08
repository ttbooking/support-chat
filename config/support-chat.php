<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Support Chat Domain
    |--------------------------------------------------------------------------
    */

    'domain' => env('CHAT_DOMAIN'),

    /*
    |--------------------------------------------------------------------------
    | Support Chat Path
    |--------------------------------------------------------------------------
    */

    'path' => env('CHAT_PATH', 'support-chat'),

    /*
    |--------------------------------------------------------------------------
    | Support Chat Route Middleware
    |--------------------------------------------------------------------------
    */

    'middleware' => ['web', 'auth'],

    /*
    |--------------------------------------------------------------------------
    | User Model and Resource
    |--------------------------------------------------------------------------
    */

    'user_model' => App\Models\User::class,
    'user_resource' => TTBooking\SupportChat\Http\Resources\UserResource::class,
    'user_cred_key' => env('CHAT_CRED_KEY', 'email'),
    'user_cred_seed' => env('CHAT_CRED_SEED'),

    /*
    |--------------------------------------------------------------------------
    | Nano ID sizes for database tables
    |--------------------------------------------------------------------------
    */

    'nanoid_size_rooms' => (int) env('CHAT_NS_ROOMS', env('CHAT_NANOID_SIZE')),
    'nanoid_size_messages' => (int) env('CHAT_NS_MESSAGES', env('CHAT_NANOID_SIZE')),

    /*
    |--------------------------------------------------------------------------
    | Disk used for attachments storage
    |--------------------------------------------------------------------------
    */

    'disk' => env('CHAT_DISK', 'support-chat'),

    /*
    |--------------------------------------------------------------------------
    | Vue Advanced Chat feature flags
    |--------------------------------------------------------------------------
    */

    'features' => [
        'show_search' => env('CHAT_SHOW_SEARCH', true),
        'show_add_room' => env('CHAT_SHOW_ADD_ROOM', true),
        'show_send_icon' => env('CHAT_SHOW_SEND_ICON', true),
        'show_files' => env('CHAT_SHOW_FILES', true),
        'show_audio' => env('CHAT_SHOW_AUDIO', true),
        'audio_bit_rate' => env('CHAT_AUDIO_BIT_RATE', 128),
        'audio_sample_rate' => env('CHAT_AUDIO_SAMPLE_RATE', 44100),
        'show_emojis' => env('CHAT_SHOW_EMOJIS', true),
        'show_reaction_emojis' => env('CHAT_SHOW_REACTION_EMOJIS', true),
        'show_new_messages_divider' => env('CHAT_SHOW_NEW_MESSAGES_DIVIDER', true),
        'show_footer' => env('CHAT_SHOW_FOOTER', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Vue Advanced Chat custom styles
    |--------------------------------------------------------------------------
    */

    'styles' => [

        'light' => [
            // <editor-fold desc="Light mode style adjustments" defaultstate="collapsed">
            'general' => [
                // 'color' => '#0a0a0a',
                // 'colorButtonClear' => '#1976d2',
                // 'colorButton' => '#fff',
                // 'backgroundColorButton' => '#1976d2',
                // 'backgroundInput' => '#fff',
                // 'colorPlaceholder' => '#9ca6af',
                // 'colorCaret' => '#1976d2',
                // 'colorSpinner' => '#333',
                // 'borderStyle' => '1px solid #e1e4e8',
                // 'backgroundScrollIcon' => '#fff',
            ],
            'container' => [
                // 'border' => 'none',
                // 'borderRadius' => '4px',
                // 'boxShadow' => '0px 1px 2px 0px rgba(0, 0, 0, 0.14), 0px 1px 5px 0px rgba(0, 0, 0, 0.12)',
            ],
            'header' => [
                // 'background' => '#fff',
                // 'colorRoomName' => '#0a0a0a',
                // 'colorRoomInfo' => '#9ca6af',
                // 'position' => 'absolute',
                // 'width' => '100%',
            ],
            'footer' => [
                // 'background' => '#f8f9fa',
                // 'borderStyleInput' => '1px solid #e1e4e8',
                // 'borderInputSelected' => '#1976d2',
                // 'backgroundReply' => '#e5e5e6',
                // 'backgroundTagActive' => '#e5e5e6',
                // 'backgroundTag' => '#f8f9fa',
            ],
            'content' => [
                // 'background' => '#f8f9fa',
            ],
            'sidemenu' => [
                // 'background' => '#fff',
                // 'backgroundHover' => '#f6f6f6',
                // 'backgroundActive' => '#e5effa',
                // 'colorActive' => '#1976d2',
                // 'borderColorSearch' => '#e1e5e8',
            ],
            'dropdown' => [
                // 'background' => '#fff',
                // 'backgroundHover' => '#f6f6f6',
            ],
            'message' => [
                // 'background' => '#fff',
                // 'backgroundMe' => '#ccf2cf',
                // 'color' => '#0a0a0a',
                // 'colorStarted' => '#9ca6af',
                // 'backgroundDeleted' => '#dadfe2',
                // 'backgroundSelected' => '#c2dcf2',
                // 'colorDeleted' => '#757e85',
                // 'colorUsername' => '#9ca6af',
                // 'colorTimestamp' => '#828c94',
                // 'backgroundDate' => '#e5effa',
                // 'colorDate' => '#505a62',
                // 'backgroundSystem' => '#e5effa',
                // 'colorSystem' => '#505a62',
                // 'backgroundMedia' => 'rgba(0, 0, 0, 0.15)',
                // 'backgroundReply' => 'rgba(0, 0, 0, 0.08)',
                // 'colorReplyUsername' => '#0a0a0a',
                // 'colorReply' => '#6e6e6e',
                // 'colorTag' => '#0d579c',
                // 'backgroundImage' => '#ddd',
                // 'colorNewMessages' => '#1976d2',
                // 'backgroundScrollCounter' => '#0696c7',
                // 'colorScrollCounter' => '#fff',
                // 'backgroundReaction' => '#eee',
                // 'borderStyleReaction' => '1px solid #eee',
                // 'backgroundReactionHover' => '#fff',
                // 'borderStyleReactionHover' => '1px solid #ddd',
                // 'colorReactionCounter' => '#0a0a0a',
                // 'backgroundReactionMe' => '#cfecf5',
                // 'borderStyleReactionMe' => '1px solid #3b98b8',
                // 'backgroundReactionHoverMe' => '#cfecf5',
                // 'borderStyleReactionHoverMe' => '1px solid #3b98b8',
                // 'colorReactionCounterMe' => '#0b59b3',
                // 'backgroundAudioRecord' => '#eb4034',
                // 'backgroundAudioLine' => 'rgba(0, 0, 0, 0.15)',
                // 'backgroundAudioProgress' => '#455247',
                // 'backgroundAudioProgressSelector' => '#455247',
                // 'colorFileExtension' => '#757e85',
            ],
            'markdown' => [
                // 'background' => 'rgba(239, 239, 239, 0.7)',
                // 'border' => 'rgba(212, 212, 212, 0.9)',
                // 'color' => '#e01e5a',
                // 'colorMulti' => '#0a0a0a',
            ],
            'room' => [
                // 'colorUsername' => '#0a0a0a',
                // 'colorMessage' => '#67717a',
                // 'colorTimestamp' => '#a2aeb8',
                // 'colorStateOnline' => '#4caf50',
                // 'colorStateOffline' => '#9ca6af',
                // 'backgroundCounterBadge' => '#0696c7',
                // 'colorCounterBadge' => '#fff',
            ],
            'emoji' => [
                // 'background' => '#fff',
            ],
            'icons' => [
                // 'search' => '#9ca6af',
                // 'add' => '#1976d2',
                // 'toggle' => '#0a0a0a',
                // 'menu' => '#0a0a0a',
                // 'close' => '#9ca6af',
                // 'closeImage' => '#fff',
                // 'file' => '#1976d2',
                // 'paperclip' => '#1976d2',
                // 'closeOutline' => '#000',
                // 'closePreview' => '#fff',
                // 'send' => '#1976d2',
                // 'sendDisabled' => '#9ca6af',
                // 'emoji' => '#1976d2',
                // 'emojiReaction' => 'rgba(0, 0, 0, 0.3)',
                // 'document' => '#1976d2',
                // 'pencil' => '#9e9e9e',
                // 'checkmark' => '#9e9e9e',
                // 'checkmarkSeen' => '#0696c7',
                // 'eye' => '#fff',
                // 'dropdownMessage' => '#fff',
                // 'dropdownMessageBackground' => 'rgba(0, 0, 0, 0.25)',
                // 'dropdownRoom' => '#9e9e9e',
                // 'dropdownScroll' => '#0a0a0a',
                // 'microphone' => '#1976d2',
                // 'audioPlay' => '#455247',
                // 'audioPause' => '#455247',
                // 'audioCancel' => '#eb4034',
                // 'audioConfirm' => '#1ba65b',
            ],
            // </editor-fold>
        ],

        'dark' => [
            // <editor-fold desc="Dark mode style adjustments" defaultstate="collapsed">
            'general' => [
                // 'color' => '#0a0a0a',
                // 'colorButtonClear' => '#1976d2',
                // 'colorButton' => '#fff',
                // 'backgroundColorButton' => '#1976d2',
                // 'backgroundInput' => '#fff',
                // 'colorPlaceholder' => '#9ca6af',
                // 'colorCaret' => '#1976d2',
                // 'colorSpinner' => '#333',
                // 'borderStyle' => '1px solid #e1e4e8',
                // 'backgroundScrollIcon' => '#fff',
            ],
            'container' => [
                // 'border' => 'none',
                // 'borderRadius' => '4px',
                // 'boxShadow' => '0px 1px 2px 0px rgba(0, 0, 0, 0.14), 0px 1px 5px 0px rgba(0, 0, 0, 0.12)',
            ],
            'header' => [
                // 'background' => '#fff',
                // 'colorRoomName' => '#0a0a0a',
                // 'colorRoomInfo' => '#9ca6af',
                // 'position' => 'absolute',
                // 'width' => '100%',
            ],
            'footer' => [
                // 'background' => '#f8f9fa',
                // 'borderStyleInput' => '1px solid #e1e4e8',
                // 'borderInputSelected' => '#1976d2',
                // 'backgroundReply' => '#e5e5e6',
                // 'backgroundTagActive' => '#e5e5e6',
                // 'backgroundTag' => '#f8f9fa',
            ],
            'content' => [
                // 'background' => '#f8f9fa',
            ],
            'sidemenu' => [
                // 'background' => '#fff',
                // 'backgroundHover' => '#f6f6f6',
                // 'backgroundActive' => '#e5effa',
                // 'colorActive' => '#1976d2',
                // 'borderColorSearch' => '#e1e5e8',
            ],
            'dropdown' => [
                // 'background' => '#fff',
                // 'backgroundHover' => '#f6f6f6',
            ],
            'message' => [
                // 'background' => '#fff',
                // 'backgroundMe' => '#ccf2cf',
                // 'color' => '#0a0a0a',
                // 'colorStarted' => '#9ca6af',
                // 'backgroundDeleted' => '#dadfe2',
                // 'backgroundSelected' => '#c2dcf2',
                // 'colorDeleted' => '#757e85',
                // 'colorUsername' => '#9ca6af',
                // 'colorTimestamp' => '#828c94',
                // 'backgroundDate' => '#e5effa',
                // 'colorDate' => '#505a62',
                // 'backgroundSystem' => '#e5effa',
                // 'colorSystem' => '#505a62',
                // 'backgroundMedia' => 'rgba(0, 0, 0, 0.15)',
                // 'backgroundReply' => 'rgba(0, 0, 0, 0.08)',
                // 'colorReplyUsername' => '#0a0a0a',
                // 'colorReply' => '#6e6e6e',
                // 'colorTag' => '#0d579c',
                // 'backgroundImage' => '#ddd',
                // 'colorNewMessages' => '#1976d2',
                // 'backgroundScrollCounter' => '#0696c7',
                // 'colorScrollCounter' => '#fff',
                // 'backgroundReaction' => '#eee',
                // 'borderStyleReaction' => '1px solid #eee',
                // 'backgroundReactionHover' => '#fff',
                // 'borderStyleReactionHover' => '1px solid #ddd',
                // 'colorReactionCounter' => '#0a0a0a',
                // 'backgroundReactionMe' => '#cfecf5',
                // 'borderStyleReactionMe' => '1px solid #3b98b8',
                // 'backgroundReactionHoverMe' => '#cfecf5',
                // 'borderStyleReactionHoverMe' => '1px solid #3b98b8',
                // 'colorReactionCounterMe' => '#0b59b3',
                // 'backgroundAudioRecord' => '#eb4034',
                // 'backgroundAudioLine' => 'rgba(0, 0, 0, 0.15)',
                // 'backgroundAudioProgress' => '#455247',
                // 'backgroundAudioProgressSelector' => '#455247',
                // 'colorFileExtension' => '#757e85',
            ],
            'markdown' => [
                // 'background' => 'rgba(239, 239, 239, 0.7)',
                // 'border' => 'rgba(212, 212, 212, 0.9)',
                // 'color' => '#e01e5a',
                // 'colorMulti' => '#0a0a0a',
            ],
            'room' => [
                // 'colorUsername' => '#0a0a0a',
                // 'colorMessage' => '#67717a',
                // 'colorTimestamp' => '#a2aeb8',
                // 'colorStateOnline' => '#4caf50',
                // 'colorStateOffline' => '#9ca6af',
                // 'backgroundCounterBadge' => '#0696c7',
                // 'colorCounterBadge' => '#fff',
            ],
            'emoji' => [
                // 'background' => '#fff',
            ],
            'icons' => [
                // 'search' => '#9ca6af',
                // 'add' => '#1976d2',
                // 'toggle' => '#0a0a0a',
                // 'menu' => '#0a0a0a',
                // 'close' => '#9ca6af',
                // 'closeImage' => '#fff',
                // 'file' => '#1976d2',
                // 'paperclip' => '#1976d2',
                // 'closeOutline' => '#000',
                // 'closePreview' => '#fff',
                // 'send' => '#1976d2',
                // 'sendDisabled' => '#9ca6af',
                // 'emoji' => '#1976d2',
                // 'emojiReaction' => 'rgba(0, 0, 0, 0.3)',
                // 'document' => '#1976d2',
                // 'pencil' => '#9e9e9e',
                // 'checkmark' => '#9e9e9e',
                // 'checkmarkSeen' => '#0696c7',
                // 'eye' => '#fff',
                // 'dropdownMessage' => '#fff',
                // 'dropdownMessageBackground' => 'rgba(0, 0, 0, 0.25)',
                // 'dropdownRoom' => '#9e9e9e',
                // 'dropdownScroll' => '#0a0a0a',
                // 'microphone' => '#1976d2',
                // 'audioPlay' => '#455247',
                // 'audioPause' => '#455247',
                // 'audioCancel' => '#eb4034',
                // 'audioConfirm' => '#1ba65b',
            ],
            // </editor-fold>
        ],

    ],

];
