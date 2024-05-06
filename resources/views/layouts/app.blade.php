<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>
 
        @filamentStyles
        @vite('resources/css/app.css')
    </head>
    <body class="font-sans antialiased">
        <livewire:empty-component />
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow sticky top-0">
                    <div class="max-w-7xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                        
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                <div class="py-12">
                    <div class="w-full mx-auto sm:px-6 lg:px-8 space-y-6">
                        
                        {{ $slot }}
                    </div>
                </div>
            </main>

            @if (isset($footer))
                <div class="bg-white sticky bottom-0">
                    <div class="flex w-full m-auto px-4 sm:px-6 lg:px-8 items-center justify-between">
                        {{ $footer }}
                    </div>
                </div>
            @endif
        </div>
        
        @filamentScripts
        @vite('resources/js/app.js')
    </body>
</html>
