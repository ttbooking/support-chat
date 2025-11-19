<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
        <h1 class="py-1.5">Choose user</h1>
        <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6 not-has-[nav]:hidden">
            <nav class="flex flex-col items-center justify-end gap-2">
                @foreach ($users as $user)
                    <a
                        href="{{ route('workbench.login', $user->email) }}"
                        class="flex items-center gap-2 min-w-60 px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                        <div style="background-image: url({{ $user->avatar }})" class="bg-cover bg-center bg-no-repeat bg-[#ddd] h-[28px] w-[28px] min-h-[28px] min-w-[28px] rounded-[50%]"></div>
                        <span>{{ $user->name }}</span>
                    </a>
                @endforeach
            </nav>
        </header>
    </body>
</html>
