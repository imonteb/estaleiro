<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
    <title>{{ $title ?? config('app.name') }}</title>
    @stack('styles')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    <x-navppal />

    <main class=" mx-auto  px-4 sm:px-6 lg:px-8 py-6">
        @if(session('message'))
        <div class="mb-4 px-4 py-3 text-sm text-green-700 bg-green-100 rounded-lg">
            {{ session('message') }}
        </div>
        @endif


        @isset($header)
        <div class="mb-6">
            <h1 class="text-center text-3xl font-bold text-blue-800 dark:text-blue-300">
                {{ $header }}
            </h1>
        </div>
        @endisset

        {{ $slot }}
    </main>
    @livewireScripts
    @stack('scripts')
</body>

</html>
