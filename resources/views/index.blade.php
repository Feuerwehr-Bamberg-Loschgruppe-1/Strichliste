<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge" />
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Strichliste</title>
        <meta name="description" content="" />
        <meta name="keywords" content="" />

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="m-6 leading-normal tracking-normal text-red-600 bg-fixed bg-cover bg-dark-gray-800" style="background-image: url('header.png');">
        <div class="h-full">
            <!--Nav-->
            <div class="container w-full mx-auto">
                <div class="flex items-center justify-between w-full">
                    <a class="flex items-center text-2xl font-bold text-[#b30000] no-underline hover:no-underline lg:text-4xl" href="#">
                        Strich<span class="text-transparent bg-clip-text bg-gradient-to-r from-[#b30000] via-red-400 to-red-300">liste</span>
                    </a>

                    <nav class="flex items-center content-center justify-end w-1/2">
                        <!-- Dark Mode Toggle Button -->
                        <button id="dark-mode-toggle" class="ml-4 text-[#b30000] hover:text-red-500 focus:outline-none">
                            <!-- Mond-Icon für den Dark Mode -->
                            <svg id="moon-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="hidden w-6 h-6 dark:block">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
                              </svg>
                            <!-- Sonnen-Icon für den Light Mode -->
                            <svg id="sun-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="block w-6 h-6 dark:hidden">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                              </svg>
                        </button>
                        @auth
                            <a href="{{ url('/admin') }}" class="inline-block h-10 p-2 text-center text-[#b30000] no-underline duration-300 ease-in-out transform hover:text-red-500 hover:text-underline md:h-auto md:p-4 hover:scale-125">
                                Backend
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="inline-block h-10 p-2 text-center text-[#b30000] no-underline duration-300 ease-in-out transform hover:text-red-500 hover:text-underline md:h-auto md:p-4 hover:scale-125">
                                Log in
                            </a>
                        @endauth
                    </nav>
                </div>
            </div>

            <!--Main-->
            <div class="container flex flex-col flex-wrap items-center justify-center pt-8 mx-auto">
                <div class="grid grid-cols-2 gap-6 px-4 py-3 shadow-inner md:grid-cols-3 xl:grid-cols-4">
                    @foreach ($users as $user)
                        <!-- Card -->
                        <div class="flex flex-col max-w-sm text-left border divide-y divide-gray-400 shadow-2xl rounded-xl bg-red-100/30 backdrop-blur-md border-white/20">
                            <!-- Card header -->
                            <div class="p-3">
                                <div class="flex items-center justify-between">
                                    <h5 class="mr-2 text-lg font-bold text-white truncate">
                                        {{ $user->name }} {{ $user->first_name }}
                                    </h5>
                                    <span class="text-xs px-2 py-1 rounded-full shadow-inner {{ $user->balance >= 0 ? 'bg-green-500 text-green-100 dark:bg-green-500 dark:text-green-100' : 'bg-red-500 text-red-100 dark:bg-red-100 dark:text-red-500' }}">
                                        {{ number_format($user->balance, 2, ',', '.') }}€
                                    </span>
                                </div>
                            </div>

                            <!-- Card body -->
                            <div class="p-2">
                                <!-- Text -->
                                <div class="flex justify-center w-full h-20 overflow-auto">
                                    <div class="flex flex-wrap gap-1">
                                        @if (count($user->items) > 0)
                                            @foreach ($user->items as $item => $count)
                                                <div class="flex items-center h-8 px-2 py-1 space-x-1 rounded-full bg-slate-200/30 dark:bg-slate-800/30">
                                                    <span class="text-xs font-medium text-white dark:text-slate-200">
                                                        {{ ucfirst($item) }}
                                                    </span>
                                                    <span class="px-2 py-1 text-xs text-white rounded-full bg-slate-600 dark:bg-slate-600">
                                                        {{ $count }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mx-auto mb-2 stroke-slate-200/30" fill="none" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m2 0a2 2 0 100-4H7a2 2 0 100 4h10zm0 0v6m0-6H7m0 0v6m0-6h10" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <!-- Card footer -->
                            @auth
                                <div class="p-2">
                                    <button class="w-full py-2 text-sm text-white rounded-md bg-red-500/80 hover:bg-red-600/80">
                                        Bezahlen
                                    </button>
                                </div>
                            @endauth
                        </div>
                        <!-- Card -->
                    @endforeach
                </div>
            </div>

                <!--Footer-->
                <div class="w-full p-8 text-sm text-center text-gray-300 md:text-left fade-in">
                    <a class="text-[#b30000] no-underline hover:no-underline" href="#">App 2024</a>
                    - Template by
                    <a class="text-[#b30000] no-underline hover:no-underline" href="#">aGr0pp.de</a>
                </div>
            </div>
        </div>
    </body>
</html>
