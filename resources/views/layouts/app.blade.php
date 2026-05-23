<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Skin Care Clinic')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-rose-50 min-h-screen">

    {{-- Navigation --}}
    <nav class="bg-white shadow-md border-b border-rose-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                {{-- Logo --}}
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-rose-500 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <div>
                        <a href="{{ url('/') }}" class="text-lg font-bold text-rose-700 leading-tight">Skin Care Clinic</a>
                        <p class="text-xs text-rose-400 leading-tight">Clinic Management</p>
                    </div>
                </div>

                {{-- Nav Links --}}
                <div class="hidden md:flex items-center gap-6">
                    <a href="{{ url('/') }}"
                       class="text-sm font-medium {{ request()->is('/') ? 'text-rose-600' : 'text-gray-600 hover:text-rose-600' }} transition">
                        Dashboard
                    </a>
                    <a href="{{ route('patients.index') }}"
                       class="text-sm font-medium {{ request()->is('patients*') ? 'text-rose-600' : 'text-gray-600 hover:text-rose-600' }} transition">
                        Patients
                    </a>
                </div>

                {{-- Register Button --}}
                <a href="{{ route('patients.create') }}"
                   class="bg-rose-500 hover:bg-rose-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    New Patient
                </a>
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
