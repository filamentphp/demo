<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">

        <meta name="application-name" content="{{ config('app.name') }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name') }}</title>

        <!-- Styles -->
        <style>[x-cloak] { display: none !important; }</style>
        @livewireStyles
        @filamentStyles
        @vite('resources/css/app.css')
        <style>
            :root {
                --font-family: Be Vietnam Pro;
                --filament-widgets-chart-font-family: var(--font-family);

                --primary-color-50: 255, 251, 235;
                --primary-color-100: 254, 243, 199;
                --primary-color-200: 253, 230, 138;
                --primary-color-300: 252, 211, 77;
                --primary-color-400: 251, 191, 36;
                --primary-color-500: 245, 158, 11;
                --primary-color-600: 217, 119, 6;
                --primary-color-700: 180, 83, 9;
                --primary-color-800: 146, 64, 14;
                --primary-color-900: 120, 53, 15;
                --secondary-color-50: 249, 250, 251;
                --secondary-color-100: 243, 244, 246;
                --secondary-color-200: 229, 231, 235;
                --secondary-color-300: 209, 213, 219;
                --secondary-color-400: 156, 163, 175;
                --secondary-color-500: 107, 114, 128;
                --secondary-color-600: 75, 85, 99;
                --secondary-color-700: 55, 65, 81;
                --secondary-color-800: 31, 41, 55;
                --secondary-color-900: 17, 24, 39;
                --gray-color-50: 249, 250, 251;
                --gray-color-100: 243, 244, 246;
                --gray-color-200: 229, 231, 235;
                --gray-color-300: 209, 213, 219;
                --gray-color-400: 156, 163, 175;
                --gray-color-500: 107, 114, 128;
                --gray-color-600: 75, 85, 99;
                --gray-color-700: 55, 65, 81;
                --gray-color-800: 31, 41, 55;
                --gray-color-900: 17, 24, 39;
                --warning-color-50: 255, 251, 235;
                --warning-color-100: 254, 243, 199;
                --warning-color-200: 253, 230, 138;
                --warning-color-300: 252, 211, 77;
                --warning-color-400: 251, 191, 36;
                --warning-color-500: 245, 158, 11;
                --warning-color-600: 217, 119, 6;
                --warning-color-700: 180, 83, 9;
                --warning-color-800: 146, 64, 14;
                --warning-color-900: 120, 53, 15;
                --danger-color-50: 254, 242, 242;
                --danger-color-100: 254, 226, 226;
                --danger-color-200: 254, 202, 202;
                --danger-color-300: 252, 165, 165;
                --danger-color-400: 248, 113, 113;
                --danger-color-500: 239, 68, 68;
                --danger-color-600: 220, 38, 38;
                --danger-color-700: 185, 28, 28;
                --danger-color-800: 153, 27, 27;
                --danger-color-900: 127, 29, 29;
                --success-color-50: 240, 253, 244;
                --success-color-100: 220, 252, 231;
                --success-color-200: 187, 247, 208;
                --success-color-300: 134, 239, 172;
                --success-color-400: 74, 222, 128;
                --success-color-500: 34, 197, 94;
                --success-color-600: 22, 163, 74;
                --success-color-700: 21, 128, 61;
                --success-color-800: 22, 101, 52;
                --success-color-900: 20, 83, 45;
            }
        </style>

        <!-- Scripts -->
        @livewireScripts
        @filamentScripts
        <script src="//unpkg.com/@alpinejs/focus" defer></script>
        <script src="//unpkg.com/alpinejs" defer></script>
        @vite('resources/js/app.js')
    </head>

    <body class="antialiased">
        {{ $slot }}
    </body>
</html>
