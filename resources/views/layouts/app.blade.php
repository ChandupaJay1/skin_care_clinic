<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Skin Care Clinic')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen flex">

@auth
{{-- ═══════════════════════════════════════════════════════════════
     SIDEBAR
════════════════════════════════════════════════════════════════ --}}
<aside id="sidebar"
    class="fixed inset-y-0 left-0 z-40 w-64 bg-white border-r border-gray-100 flex flex-col shadow-sm
           transform -translate-x-full lg:translate-x-0 transition-transform duration-200">

    {{-- Brand --}}
    <div class="flex items-center gap-3 px-5 h-16 border-b border-gray-100 flex-shrink-0">
        <div class="w-9 h-9 bg-rose-500 rounded-xl flex items-center justify-center shadow-sm shadow-rose-200">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-sm font-bold text-gray-800 leading-tight">Skin Care Clinic</p>
            <p class="text-xs text-gray-400 leading-tight">Management System</p>
        </div>
    </div>

    {{-- Nav --}}
    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-0.5">

        <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest px-3 mb-2">Main</p>

        <a href="{{ route('dashboard') }}"
            class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                   {{ request()->routeIs('dashboard') ? 'bg-rose-50 text-rose-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
            <svg class="w-4.5 h-4.5 w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard
        </a>

        <a href="{{ route('patients.index') }}"
            class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                   {{ request()->is('patients*') ? 'bg-rose-50 text-rose-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Patients
        </a>

        <a href="{{ route('doctors.index') }}"
            class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                   {{ request()->is('doctors*') ? 'bg-rose-50 text-rose-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Doctors
        </a>

        <a href="{{ route('treatments.index') }}"
            class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                   {{ request()->is('treatments*') ? 'bg-rose-50 text-rose-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
            </svg>
            Treatments
        </a>

        <a href="{{ route('appointments.index') }}"
            class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                   {{ request()->is('appointments*') ? 'bg-rose-50 text-rose-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Appointments
        </a>

        <a href="{{ route('invoices.index') }}"
            class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                   {{ request()->is('invoices*') ? 'bg-rose-50 text-rose-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Invoices
        </a>

        <div class="pt-4 mt-2 border-t border-gray-100">
            @if(auth()->user()->isAdmin())
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest px-3 mb-2">Reports</p>
            <a href="{{ route('reports.daily') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                       {{ request()->routeIs('reports.daily') ? 'bg-rose-50 text-rose-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707"/>
                </svg>
                Daily Summary
            </a>
            <a href="{{ route('reports.monthly') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                       {{ request()->routeIs('reports.monthly') ? 'bg-rose-50 text-rose-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Monthly Revenue
            </a>
            <a href="{{ route('reports.outstanding') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                       {{ request()->routeIs('reports.outstanding') ? 'bg-rose-50 text-rose-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Outstanding
            </a>
            @endif
        </div>

    </nav>

    {{-- User card at bottom --}}
    <div class="flex-shrink-0 border-t border-gray-100 p-3">
        @php
            $roleColors = [
                'admin'        => 'bg-rose-100 text-rose-700',
                'doctor'       => 'bg-teal-100 text-teal-700',
                'receptionist' => 'bg-blue-100 text-blue-700',
            ];
            $rc = $roleColors[auth()->user()->role] ?? 'bg-gray-100 text-gray-600';
        @endphp
        <div class="flex items-center gap-3 px-2 py-2 rounded-xl hover:bg-gray-50 transition">
            <div class="w-8 h-8 rounded-full bg-rose-500 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-xs font-semibold text-gray-700 truncate">{{ auth()->user()->name }}</p>
                <span class="text-xs px-1.5 py-0.5 rounded-full font-medium {{ $rc }}">
                    {{ auth()->user()->role_label }}
                </span>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" title="Sign Out"
                    class="text-gray-400 hover:text-rose-500 transition p-1 rounded-lg hover:bg-rose-50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>

</aside>

{{-- Mobile overlay --}}
<div id="sidebar-overlay"
    class="fixed inset-0 z-30 bg-black/40 backdrop-blur-sm hidden lg:hidden"
    onclick="closeSidebar()"></div>
@endauth

{{-- ═══════════════════════════════════════════════════════════════
     MAIN AREA
════════════════════════════════════════════════════════════════ --}}
<div class="flex-1 flex flex-col min-h-screen {{ auth()->check() ? 'lg:ml-64' : '' }}">

    {{-- Top bar --}}
    <header class="bg-white border-b border-gray-100 h-16 flex items-center px-4 sm:px-6 gap-4 flex-shrink-0 sticky top-0 z-20">

        @auth
        {{-- Mobile hamburger --}}
        <button onclick="openSidebar()" class="lg:hidden text-gray-500 hover:text-gray-700 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
        @endauth

        {{-- Page title slot --}}
        <div class="flex-1">
            <h1 class="text-sm font-semibold text-gray-700">@yield('page_title', 'Dashboard')</h1>
            @hasSection('page_subtitle')
            <p class="text-xs text-gray-400">@yield('page_subtitle')</p>
            @endif
        </div>

        @auth
        {{-- Right: user pill (desktop) --}}
        <div class="hidden sm:flex items-center gap-2">
            @php
                $roleColors2 = ['admin'=>'bg-rose-100 text-rose-700','doctor'=>'bg-teal-100 text-teal-700','receptionist'=>'bg-blue-100 text-blue-700'];
                $rc2 = $roleColors2[auth()->user()->role] ?? 'bg-gray-100 text-gray-600';
            @endphp
            <span class="text-xs px-2.5 py-1 rounded-full font-medium {{ $rc2 }}">
                {{ auth()->user()->role_label }}
            </span>
            <span class="text-xs text-gray-500 font-medium">{{ auth()->user()->name }}</span>
        </div>
        @endauth
    </header>

    {{-- Flash messages --}}
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
        class="mx-4 sm:mx-6 mt-4">
        <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl px-4 py-3 flex items-center gap-3 shadow-sm">
            <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                <svg class="w-3.5 h-3.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <p class="text-sm">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="mx-4 sm:mx-6 mt-4">
        <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3 flex items-center gap-3 shadow-sm">
            <div class="w-6 h-6 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                <svg class="w-3.5 h-3.5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
            <p class="text-sm">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    {{-- Content --}}
    <main class="flex-1 px-4 sm:px-6 py-6">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="border-t border-gray-100 px-6 py-3 flex items-center justify-between">
        <p class="text-xs text-gray-400">&copy; {{ date('Y') }} Skin Care Clinic</p>
        <p class="text-xs text-gray-300">v1.0</p>
    </footer>

</div>

<script>
function openSidebar() {
    document.getElementById('sidebar').classList.remove('-translate-x-full');
    document.getElementById('sidebar-overlay').classList.remove('hidden');
}
function closeSidebar() {
    document.getElementById('sidebar').classList.add('-translate-x-full');
    document.getElementById('sidebar-overlay').classList.add('hidden');
}
</script>

</body>
</html>
