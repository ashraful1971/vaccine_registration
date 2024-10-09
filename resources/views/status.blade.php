@extends('layouts.app')

@section('content')
    <div class="text-center max-w-md w-full mx-auto border border-gray-300 rounded-2xl p-8 mt-4">
        <x-logo />

        @if (isset($user))
            <div>
                <h2 class="text-xl font-bold mb-2">Hello, {{ $user->name }}</h2>
                Status: <strong>{{ str($user->status)->headline() }}</strong><br>
                @if ($user->vaccine_scheduled_at)
                    Date: <strong>{{ $user->vaccine_scheduled_at->format('M d, Y') }}</strong><br>
                @endif
            </div>
        @else
            <div>
                Status: <strong>Not Registered</strong><br>
                You can <a class="text-blue-500" href="{{ route('register.index') }}">register now</a> for the vaccination
            </div>
        @endif
    </div>

    <div class="text-center mt-4"><a class="text-center inline-block px-4 py-2 rounded bg-black text-white"
            href="{{ route('search.index') }}">Go Back</a></div>
@endsection
