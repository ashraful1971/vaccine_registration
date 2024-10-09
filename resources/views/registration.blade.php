@extends('layouts.app')

@section('content')
    <div class="max-w-md w-full mx-auto border border-gray-300 rounded-2xl p-8">
        <x-logo />

        @if (session('success'))
            <div class="px-4 py-2 mb-4 rounded bg-green-100 border border-green-200 text-green-600">{{ session('success') }}
            </div>
        @endif

        <form action="{{ route('register.store') }}" method="POST">
            @csrf
            <div class="space-y-6">
                <x-input label="Name" name="name" :value="old('name')" />
                <x-input label="Email" type="email" name="email" :value="old('email')" />
                <x-input label="Phone" name="phone" :value="old('phone')" />
                <x-input label="NID" name="nid" :value="old('nid')" />
                <x-input label="Vaccine Center" type="select" name="vaccine_center_id" :value="old('vaccine_center_id')"
                    :options="$vaccineCenters" />
            </div>

            <div class="!mt-12">
                <button type="submit"
                    class="w-full py-3 px-4 text-sm tracking-wider font-semibold rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none">
                    Register
                </button>
            </div>
            <p class="text-gray-800 text-sm mt-6 text-center">Already registered? <a href="{{ route('search.index') }}"
                    class="text-blue-600 font-semibold hover:underline ml-1">Check status here</a></p>
        </form>
    </div>
@endsection
