<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-b from-slate-50 to-slate-100 px-4">
        <div class="w-full max-w-md">
            {{-- Header --}}
            <!-- <div class="text-center mb-6"> -->
                <!-- <div class="mx-auto w-14 h-14 rounded-2xl bg-blue-600/10 flex items-center justify-center">
                    {{-- Laravel-ish icon --}}
                    <svg class="w-8 h-8 text-blue-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                              d="M12 2l8 4v6c0 5-3.5 9.4-8 10-4.5-.6-8-5-8-10V6l8-4z"/>
                    </svg>
                </div> -->
                 <!-- <h1 class="mt-4 text-2xl font-bold text-slate-900">تسجيل الدخول</h1>
                <p class="mt-1 text-sm text-slate-500">ادخل بياناتك للوصول إلى لوحة النظام</p>
                -->
            <!-- </div> -->

            {{-- Card --}}
            <div class="bg-white/90 backdrop-blur rounded-2xl shadow-sm ring-1 ring-slate-200 p-6 text-center mb-6">
                {{-- Session Status --}}
                <x-auth-session-status class="mb-4" :status="session('status')" />
                 <h1 class="mt-4 text-2xl font-bold text-slate-900">تسجيل الدخول</h1>
                <p class="mt-1 text-sm text-slate-500">ادخل بياناتك للوصول إلى لوحة النظام</p>
                <div class="flex justify-center mb-6">
                    <img src="{{ asset('images/logo.png') }}"
                        alt="Logo"
                        class="h-16 object-contain">
                </div>
                <form method="POST" action="{{ route('login') }}" class="space-y-5" dir="rtl">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">
                            البريد الإلكتروني
                        </label>

                        <div class="relative">
                            <span class="absolute inset-y-0 right-3 flex items-center text-slate-400">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 8l-9 6-9-6m18 0v10a2 2 0 01-2 2H5a2 2 0 01-2-2V8m18 0a2 2 0 00-2-2H5a2 2 0 00-2 2" />
                                </svg>
                            </span>

                            <input id="email"
                                name="email"
                                type="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="name@company.com"
                                class="w-full rounded-xl border border-slate-200 bg-white pr-11 pl-4 py-3 text-sm
                                          focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10
                                          @error('email') border-red-400 focus:border-red-500 focus:ring-red-500/10 @enderror">
                        </div>

                        @error('email')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700 mb-1.5">
                            كلمة المرور
                        </label>

                        <div class="relative">
                            <span class="absolute inset-y-0 right-3 flex items-center text-slate-400">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 11c1.657 0 3-1.343 3-3S13.657 5 12 5 9 6.343 9 8s1.343 3 3 3z" />
                                    <path stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"
                                        d="M19 21v-1a4 4 0 00-4-4H9a4 4 0 00-4 4v1" />
                                </svg>
                            </span>

                            <input id="password"
                                name="password"
                                type="password"
                                required
                                autocomplete="current-password"
                                placeholder="••••••••"
                                class="w-full rounded-xl border border-slate-200 bg-white pr-11 pl-4 py-3 text-sm
                                          focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10
                                          @error('password') border-red-400 focus:border-red-500 focus:ring-red-500/10 @enderror">
                        </div>

                        @error('password')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- {{-- Remember + Forgot --}}
                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="inline-flex items-center gap-2 text-sm text-slate-600">
                            <input id="remember_me"
                                   type="checkbox"
                                   name="remember"
                                   class="rounded border-slate-300 text-blue-600 focus:ring-blue-500/20">
                            تذكرني
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                               class="text-sm text-blue-700 hover:text-blue-800 font-medium">
                                نسيت كلمة المرور؟
                            </a>
                        @endif
                    </div> -->

                    {{-- Submit --}}
                    <button type="submit"
                        class="w-full rounded-xl bg-blue-600 hover:bg-blue-700 text-white py-3 font-semibold
                                   shadow-sm transition focus:outline-none focus:ring-4 focus:ring-blue-500/20">
                        تسجيل الدخول
                    </button>

                    <!-- {{-- Footer hint --}}
                    <p class="text-center text-xs text-slate-500">
                        بالاستمرار، أنت توافق على سياسات الاستخدام الداخلية للنظام.
                    </p> -->
                </form>
            </div>

            {{-- Bottom --}}
            <p class="text-center mt-6 text-xs text-slate-400">
                © {{ date('Y') }} Attendance System
            </p>
        </div>
    </div>
</x-guest-layout>