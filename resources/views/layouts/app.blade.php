<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Skin Care Clinic')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-rose-50 min-h-screen">

    {{-- Top Bar --}}
    <nav class="bg-white shadow-sm border-b border-rose-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-14">

                {{-- Brand --}}
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-rose-500 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <div>
                        <a href="{{ route('dashboard') }}" class="text-base font-bold text-rose-700 leading-tight">Skin Care Clinic</a>
                        <p class="text-xs text-rose-400 leading-tight">Clinic Management</p>
                    </div>
                </div>

                {{-- User info + logout --}}
                @auth
                <div class="flex items-center gap-3">
                    <div class="hidden sm:flex items-center gap-2">
                        @php
                            $roleColors = ['admin'=>'bg-rose-100 text-rose-700','doctor'=>'bg-teal-100 text-teal-700','receptionist'=>'bg-blue-100 text-blue-700'];
                            $roleColor  = $roleColors[auth()->user()->role] ?? 'bg-gray-100 text-gray-600';
                        @endphp
                        <div class="w-7 h-7 rounded-full bg-rose-500 flex items-center justify-center text-white text-xs font-bold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="leading-tight">
                            <p class="text-xs font-semibold text-gray-700">{{ auth()->user()->name }}</p>
                            <span class="text-xs px-1.5 py-0.5 rounded-full font-medium {{ $roleColor }}">
                                {{ auth()->user()->role_label }}
                            </span>
                        </div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="flex items-center gap-1.5 text-xs text-gray-500 hover:text-rose-600 border border-gray-200 hover:border-rose-300 px-3 py-1.5 rounded-lg transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Sign Out
                        </button>
                    </form>
                </div>
                @endauth

            </div>
        </div>
    </nav>

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        <div class="bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3 flex items-center gap-3">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        <div class="bg-red-50 border border-red-200 text-red-800 rounded-lg px-4 py-3 flex items-center gap-3">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            {{ session('error') }}
        </div>
    </div>
    @endif

    {{-- Main Content --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-white border-t border-rose-100 mt-12">
        <div class="max-w-7xl mx-auto px-4 py-4 text-center text-sm text-gray-400">
            &copy; {{ date('Y') }} Skin Care Clinic. All rights reserved.
        </div>
    </footer>

</body>
</html>
