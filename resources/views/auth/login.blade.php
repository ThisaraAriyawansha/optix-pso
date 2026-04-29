<x-layouts.login>
    <form method="POST" action="{{ route('login') }}" x-data="{ showPassword: false }">
        @csrf

        {{-- Email --}}
        <div class="mb-5">
            <label for="email" class="block text-sm font-medium text-[#1A202C] mb-1.5">Email address</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-[#64748B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                       placeholder="you@company.com"
                       class="w-full pl-9 pr-4 py-2.5 text-sm text-[#1A202C] bg-white border rounded-lg outline-none transition
                              placeholder:text-[#94A3B8]
                              @error('email') border-[#DC2626] focus:ring-[#DC2626]/20 @else border-[#E2E8F0] focus:border-[#004080] focus:ring-2 focus:ring-[#004080]/20 @enderror">
            </div>
            @error('email')
                <p class="mt-1.5 text-xs text-[#DC2626]">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div class="mb-5">
            <label for="password" class="block text-sm font-medium text-[#1A202C] mb-1.5">Password</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-[#64748B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <input id="password" :type="showPassword ? 'text' : 'password'" name="password" required
                       class="w-full pl-9 pr-10 py-2.5 text-sm text-[#1A202C] bg-white border rounded-lg outline-none transition
                              @error('password') border-[#DC2626] focus:ring-[#DC2626]/20 @else border-[#E2E8F0] focus:border-[#004080] focus:ring-2 focus:ring-[#004080]/20 @enderror">
                <button type="button" @click="showPassword = !showPassword"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-[#64748B] hover:text-[#1A202C] transition">
                    <svg x-show="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg x-show="showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    </svg>
                </button>
            </div>
            @error('password')
                <p class="mt-1.5 text-xs text-[#DC2626]">{{ $message }}</p>
            @enderror
        </div>

        {{-- Remember me --}}
        <div class="flex items-center justify-between mb-6">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="remember"
                       class="w-4 h-4 rounded border-[#E2E8F0] text-[#004080] focus:ring-[#004080]/20">
                <span class="text-sm text-[#64748B]">Remember me</span>
            </label>
            <a href="#" class="text-sm text-[#004080] hover:text-[#0066CC] transition">Forgot password?</a>
        </div>

        {{-- Submit --}}
        <button type="submit"
                class="w-full flex items-center justify-center py-2.5 px-4 text-base font-medium text-white bg-[#004080] hover:bg-[#003366] rounded-lg transition focus:outline-none focus:ring-2 focus:ring-[#004080]/40">
            Sign In
        </button>

    </form>
</x-layouts.login>