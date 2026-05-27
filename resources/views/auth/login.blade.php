<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — Skin Care Clinic</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex">

    {{-- Left panel — branding --}}
    <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-rose-600 via-rose-500 to-pink-500 flex-col justify-between p-12 relative overflow-hidden">

        {{-- Decorative circles --}}
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-white/10 rounded-full"></div>
        <div class="absolute -bottom-32 -right-20 w-[28rem] h-[28rem] bg-white/10 rounded-full"></div>
        <div class="absolute top-1/2 left-1/3 w-48 h-48 bg-white/5 rounded-full"></div>

        {{-- Logo --}}
        <div class="relative z-10 flex items-center gap-3">
            <div class="w-10 h-10 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </div>
            <span class="text-white font-bold text-lg">Skin Care Clinic</span>
        </div>

        {{-- Hero text --}}
        <div class="relative z-10">
            <h1 class="text-4xl font-bold text-white leading-tight mb-4">
                Welcome back to<br>your clinic portal
            </h1>
            <p class="text-rose-100 text-base leading-relaxed max-w-sm">
                Manage patients, doctors, and treatments all in one place. Streamlined for your team.
            </p>

            {{-- Feature pills --}}
            <div class="flex flex-wrap gap-2 mt-8">
                <span class="bg-white/20 text-white text-xs font-medium px-3 py-1.5 rounded-full backdrop-blur">Patient Records</span>
                <span class="bg-white/20 text-white text-xs font-medium px-3 py-1.5 rounded-full backdrop-blur">Doctor Profiles</span>
                <span class="bg-white/20 text-white text-xs font-medium px-3 py-1.5 rounded-full backdrop-blur">Treatment Plans</span>
                <span class="bg-white/20 text-white text-xs font-medium px-3 py-1.5 rounded-full backdrop-blur">Role-based Access</span>
            </div>
        </div>

        {{-- Bottom tagline --}}
        <div class="relative z-10">
            <p class="text-rose-200 text-xs">&copy; {{ date('Y') }} Skin Care Clinic. All rights reserved.</p>
        </div>
    </div>

    {{-- Right panel — form --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center bg-gray-50 p-6 sm:p-12">
        <div class="w-full max-w-md">

            {{-- Mobile logo --}}
            <div class="lg:hidden text-center mb-8">
                <div class="w-14 h-14 bg-rose-500 rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-lg shadow-rose-200">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
                <h1 class="text-xl font-bold text-gray-800">Skin Care Clinic</h1>
            </div>

            <h2 class="text-2xl font-bold text-gray-800 mb-1">Sign in</h2>
            <p class="text-gray-400 text-sm mb-8">Enter your credentials to access the system</p>

            {{-- Error alert --}}
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 mb-6 flex items-start gap-3">
                <svg class="w-4 h-4 text-red-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <p class="text-red-700 text-sm">{{ $errors->first() }}</p>
            </div>
            @endif

            <form action="{{ route('login.submit') }}" method="POST" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Email Address</label>
                    <div class="relative">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <input type="email" name="email" value="{{ old('email') }}"
                            placeholder="you@clinic.com"
                            autofocus autocomplete="email"
                            class="w-full pl-10 pr-4 py-3 text-sm bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-400 focus:border-transparent transition @error('email') border-red-300 bg-red-50 @enderror">
                    </div>
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                    <div class="relative">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <input type="password" name="password" id="password_field"
                            placeholder="••••••••"
                            autocomplete="current-password"
                            class="w-full pl-10 pr-11 py-3 text-sm bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-400 focus:border-transparent transition @error('password') border-red-300 bg-red-50 @enderror">
                        <button type="button" onclick="togglePassword()"
                            class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition p-0.5">
                            <svg id="eye_icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Remember --}}
                <div class="flex items-center gap-2.5">
                    <input type="checkbox" name="remember" id="remember"
                        class="w-4 h-4 text-rose-500 border-gray-300 rounded focus:ring-rose-400 cursor-pointer">
                    <label for="remember" class="text-sm text-gray-600 cursor-pointer select-none">Keep me signed in</label>
                </div>

                {{-- Submit --}}
                <button type="submit"
                    class="w-full bg-rose-500 hover:bg-rose-600 active:bg-rose-700 text-white font-semibold py-3 rounded-xl transition shadow-sm shadow-rose-200 flex items-center justify-center gap-2 text-sm mt-2">
                    Sign In
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </button>
            </form>

            {{-- Role badges --}}
            <div class="mt-8 pt-6 border-t border-gray-200">
                <p class="text-xs text-gray-400 text-center mb-3">Access levels</p>
                <div class="flex justify-center gap-2">
                    <span class="inline-flex items-center gap-1.5 text-xs bg-rose-50 text-rose-600 border border-rose-100 px-3 py-1.5 rounded-full font-medium">
                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>Admin
                    </span>
                    <span class="inline-flex items-center gap-1.5 text-xs bg-teal-50 text-teal-600 border border-teal-100 px-3 py-1.5 rounded-full font-medium">
                        <span class="w-1.5 h-1.5 rounded-full bg-teal-500"></span>Doctor
                    </span>
                    <span class="inline-flex items-center gap-1.5 text-xs bg-blue-50 text-blue-600 border border-blue-100 px-3 py-1.5 rounded-full font-medium">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>Receptionist
                    </span>
                </div>
            </div>

        </div>
    </div>

</body>
<script>
function togglePassword() {
    const f = document.getElementById('password_field');
    const i = document.getElementById('eye_icon');
    if (f.type === 'password') {
        f.type = 'text';
        i.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>';
    } else {
        f.type = 'password';
        i.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
    }
}
</script>
</html>
