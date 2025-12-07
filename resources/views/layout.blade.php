<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <link rel="shortcut icon" href="{{ Vite::app('support-chat')->asset('resources/images/favicon.svg') }}" />

        <title>Support Chat - {{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link rel="stylesheet" href="https://fonts.bunny.net/css?family=nunito:400,500,600&display=swap" />

        <!-- Scripts -->
        @vite('resources/js/app.js')
        @chat($roomId, $features)
    </head>
    <body class="font-sans antialiased">
        <div id="standalone-chat" v-cloak></div>
    </body>
</html>
