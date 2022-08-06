<h1>Hello World !!!</h1>

{{-- ここのhrefはroutesで設定したRouteファサードのパス --}}
<a href="{{ url('/test/component-test1') }}">component-test1</a>
<a href="{{ url('/test/component-test2') }}">component-test2</a>

<div
    class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0">
    @if (Route::has('tests.login'))
        <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
            @auth('tests')
                <a href="{{ url('/tests/dashboard') }}"
                    class="text-sm text-gray-700 dark:text-gray-500 underline">Dashboard</a>
            @else
                <a href="{{ route('tests.login') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Log
                    in</a>

                @if (Route::has('tests.register'))
                    <a href="{{ route('tests.register') }}"
                        class="ml-4 text-sm text-gray-700 dark:text-gray-500 underline">Register</a>
                @endif
            @endauth
        </div>
    @endif
</div>
