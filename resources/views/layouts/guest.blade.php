<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Auth</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased overflow-hidden">
        <div class="min-h-screen flex">
            <!-- Left Side (Image) -->
            <div class="hidden lg:flex lg:w-1/2 relative bg-gray-900 items-center justify-center">
                <!-- Using a beautiful unsplash image as placeholder for nature/village -->
                <div class="absolute inset-0 bg-cover bg-center opacity-60" style="background-image: url('https://images.unsplash.com/photo-1506929562872-bb421503ef21?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/40 to-transparent"></div>
                <div class="relative z-10 text-center px-12">
                    <h1 class="text-5xl font-bold text-white mb-6 tracking-tight leading-tight">Selamat Datang di<br>Portal Desa</h1>
                    <p class="text-xl text-gray-200 font-light">Jelajahi keindahan alam, kekayaan budaya, dan kearifan lokal bersama kami.</p>
                </div>
            </div>

            <!-- Right Side (Form) -->
            <div class="w-full lg:w-1/2 flex flex-col items-center justify-center p-8 bg-white sm:p-12 relative">
                <!-- Mobile Background (visible only on small screens) -->
                <div class="absolute inset-0 bg-cover bg-center opacity-10 lg:hidden" style="background-image: url('https://images.unsplash.com/photo-1506929562872-bb421503ef21?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');"></div>
                
                <div class="w-full max-w-md relative z-10 bg-white/80 lg:bg-transparent backdrop-blur-md lg:backdrop-blur-none p-8 lg:p-0 rounded-2xl shadow-xl lg:shadow-none border border-gray-100 lg:border-none">
                    <div class="text-center lg:text-left mb-8">
                        <a href="/" class="inline-block mb-4">
                            <x-application-logo class="w-14 h-14 fill-current text-indigo-600 mx-auto lg:mx-0" />
                        </a>
                    </div>

                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
