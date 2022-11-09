<!DOCTYPE html>
<html lang="{{ config('app.locale', 'en') }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="shortcut icon" href="{{ asset('/vendor/support-chat/img/favicon.png') }}" />

    <title>Support Chat{{ config('app.name') ? ' - ' . config('app.name') : '' }}</title>

    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" />

    @vite('resources/js/app.js')
    {{
        Vite::useHotFile('vendor/support-chat/hot')
            ->useBuildDirectory('vendor/support-chat/build')
            ->withEntryPoints(['resources/js/app.js'])
    }}
</head>
<body>
<div id="support-chat" v-cloak>
    <support-chat user-id="{{ auth()->id() }}"></support-chat>
</div>

<!-- Global Support Chat Object -->
<script type="text/javascript">
    window.SupportChat = @json($supportChatScriptVariables);
</script>

</body>
</html>
