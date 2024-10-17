<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Strichliste</title>

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
            <!-- Hintergrundbild -->
            <img id="background" class="absolute -left-20 top-0 max-w-[877px]" src="https://laravel.com/assets/img/welcome/background.svg" />
            <div class="relative min-h-screen flex flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white">
                <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                    <!-- Header-Bereich -->
                    <header class="grid items-center grid-cols-2 gap-2 py-10 lg:grid-cols-3">
                        <div class="flex lg:justify-center lg:col-start-2">
                            <!-- Header Logo - Text -->
                            <p> Strichliste </p>
                        </div>
                        @if (Route::has('login'))
                            <!-- Navigation für eingeloggte Benutzer -->
                            <livewire:welcome.navigation />
                        @endif
                    </header>

                    <!-- Hauptinhalt -->
                    <main class="mt-6">
                        <div class="grid gap-6 lg:grid-cols-2 lg:gap-8">
                            <!-- Inhalt hier einfügen -->
                        </div>
                    </main>

                    <!-- Footer-Bereich -->
                    <footer class="py-16 text-sm text-center text-black dark:text-white/70">
                        <!-- Footer-Inhalt hier einfügen -->
                        Strichliste der Löschgruppe 1 Feuerwehr Bamberg
                    </footer>
                </div>
            </div>
        </div>
    </body>
</html>
