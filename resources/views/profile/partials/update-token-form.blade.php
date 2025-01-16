<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Access Token') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's access token.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.refresh') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <input type="hidden" name="name" value="{{ $user->name }}">
        <input type="hidden" name="email" value="{{ $user->email }}">
        <input type="hidden" name="refresh_token" value="{{ $user->refresh_token }}">

        <div>
            <x-input-label for="access_token" :value="__('Access Token')" />
            <x-text-input id="access_token" name="access_token" type="text" class="mt-1 block w-full" :value="old('access_token', $user->access_token)" autofocus autocomplete="access_token" />
            <x-input-error class="mt-2" :messages="$errors->get('access_token')" />
        </div>

       {{--  <div>
            <x-input-label for="expires_in" :value="__('Expires In')" /> --}}
            <x-text-input id="expires_in" name="expires_in" type="hidden" class="mt-1 block w-full" :value="old('expires_in', $user->expires_in)" autocomplete="expires_in" />
            {{-- <x-input-error class="mt-2" :messages="$errors->get('expires_in')" />
        </div> --}}

        <div class="flex items-center gap-4">
            <x-secondary-button onclick="location.href='{{ route('adobe.login') }}'">{{ __('Generate New Token') }}</x-secondary-button>
            <x-primary-button>{{ __('Refresh Token') }}</x-primary-button>

            @if (session('status') === 'token-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Updated.') }}</p>
            @endif

            @if (session('status') === 'token-refreshed')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Refreshed.') }}</p>
            @endif
        </div>
    </form>
</section>
