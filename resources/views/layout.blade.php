<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <link rel="shortcut icon" href="{{ Vite::supportChatImage('favicon.svg') }}" />

        <title>Support Chat - {{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link rel="stylesheet" href="https://fonts.bunny.net/css?family=nunito:400,500,600&display=swap" />

        <!-- Scripts -->
        @vite('resources/js/app.js')
        {{ Vite::supportChatEntryPoint() }}
    </head>
    <body class="font-sans antialiased">
        <div id="support-chat" v-cloak>
            <support-chat></support-chat>
        </div>

        <!-- Global Support Chat Object -->
        <script type="text/javascript">
            window.SupportChat = @json($supportChatScriptVariables);
        </script>
    </body>
</html>
