<x-guest-layout>
        <main class="main-auth">
            <div class="main-auth__info">
                <h1>RecetApp</h1>
                <h2>Login</h2>

                <x-jet-validation-errors class="mb-4" />

                @if (session('status'))
                    <div class="mb-4 font-medium text-sm text-green-600">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div>
                        <x-jet-label for="email" value="{{ __('Email') }}" class="text-md"/>
                        <x-jet-input id="email" class="form-input block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
                    </div>

                    <div class="mt-4">
                        <x-jet-label for="password" value="{{ __('Password') }}" />
                        <x-jet-input id="password" class="form-input block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
                    </div>

                    <div class="block mt-4">
                        <label for="remember_me" class="flex items-center">
                            <x-jet-checkbox id="remember_me" name="remember" class="form-checkbox"/>
                            <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        @if (Route::has('password.request'))
                            <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                                {{ __('Forgot your password?') }}
                            </a>
                        @endif

                        <input type="submit" class="boton boton--azul" value="ENTRAR">

                    </div>

                    {{-- Login with Facebook --}}
                    <div class="flex items-center justify-end mt-4" style="display:none;">
                        <a class="btn" href="{{ url('auth/facebook') }}"
                            style="background: #3B5499; color: #ffffff; padding: 10px; width: 100%; text-align: center; display: block; border-radius:3px;">
                            Login with Facebook
                        </a>
                    </div>

                </form>
            </div>
            
            <div class="main-auth__imagen">
                <img src="images/food.jpg" alt="RecetApp">
                </div>
        </main>
</x-guest-layout>
