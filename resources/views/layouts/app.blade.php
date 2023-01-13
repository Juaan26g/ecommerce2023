<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        @livewireStyles
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            const defaultTheme = require('tailwindcss/defaultTheme');
            const colors = require('tailwindcss/colors');
            module.exports = {
                content: [
                    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
                    './vendor/laravel/jetstream/**/*.blade.php',
                    './storage/framework/views/*.php',
                    './resources/views/**/*.blade.php',
                ],
            
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Nunito', ...defaultTheme.fontFamily.sans],
                        },
                        colors: {
                            trueGray: colors.neutral,
                            orange: colors.orange,
                            lime: colors.lime,
                        }
                    },
                },
            
                plugins: [require('@tailwindcss/forms'), require('@tailwindcss/typography')],
            };</script>
        <!-- Scripts -->
        
    </head>
    <body class="font-sans antialiased">
        <x-jet-banner />

        <div class="min-h-screen bg-gray-100">
            @livewire('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @stack('modals')

        @livewireScripts
    </body>
</html>
