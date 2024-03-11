@extends('app.layout')

@section('title', 'Homepage')

@section('content')
    @guest
        <div class="relative mt-12 sm:max-w-xl sm:mx-auto">
            <div class="relative px-4 py-2 shadow-lg sm:rounded-3xl sm:p-20">
                <div class="max-w-md mx-auto">
                    <div class="divide-y divide-gray-200">
                        <div
                            class="py-1 px-12 text-base leading-6 space-y-4 text-gray-700 sm:text-lg sm:leading-7 items-center">
                            <p class="flex justify-center">
                                <img
                                    src="data:image/png;base64,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"
                                    alt="">
                            </p>
                            <div class="w-full max-w-xs">
                                <form method="POST" action="{{ route('register') }}">
                                    @csrf

                                    <div>
                                        <label for="name" value="{{ __('Name') }}"></label>
                                        <input id="name" class="block mt-1 w-full" type="text" name="name"
                                               value="{{ old('name') }}" required
                                               autofocus autocomplete="name"/>
                                    </div>

                                    <div>
                                        <label for="email" value="{{ __('Email') }}"></label>
                                        <input id="email" class="block mt-1 w-full" type="email" name="email"
                                               value="{{ old('email') }}" required/>
                                    </div>

                                    <div>
                                        <label for="password" value="{{ __('Password') }}"></label>
                                        <input id="password" class="block mt-1 w-full" type="password" name="password"
                                               required
                                               autocomplete="new-password"/>
                                    </div>

                                    <div>
                                        <label for="password_confirmation" value="{{ __('Confirm Password') }}"></label>
                                        <input id="password_confirmation" class="block mt-1 w-full" type="password"
                                               name="password_confirmation" required autocomplete="new-password"/>
                                    </div>

                                    <div>
                                        <a href="{{ route('login') }}">
                                            {{ __('Already registered?') }}
                                        </a>
                                        <button>
                                            {{ __('Register') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endguest
    @auth()
        @php
            header('Location: /leads');
        @endphp
    @endauth
@endsection()
