@php
    $back = route('dashboard');
@endphp

@extends('layouts.app')

@section('title', 'Registration Codes')

@section('content')
<div class="container mx-auto px-4 sm:px-6 max-w-2xl">
    <div class="bg-white rounded-lg shadow-md p-6 mt-6">
        <h1 class="text-2xl font-bold mb-6">Registration Codes</h1>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="space-y-6">
            <div class="border rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold">Admin Code</h2>
                        <p class="text-sm text-gray-500">Use this code to register as an Admin</p>
                    </div>
                </div>
                <div class="mt-3 flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                    <code class="bg-gray-100 px-4 py-2.5 rounded text-base sm:text-lg font-mono select-all break-all text-center sm:text-left">
                        {{ $adminCode ?? '— No code set —' }}
                    </code>
                    <form method="POST" action="{{ route('admin.settings.regenerate-admin') }}" class="sm:inline">
                        @csrf
                        <button type="submit" class="w-full sm:w-auto bg-blue-600 text-white px-4 py-2.5 rounded hover:bg-blue-700 text-sm"
                            onclick="return confirm('Change Admin code? The old code will stop working immediately.')">
                            Regenerate
                        </button>
                    </form>
                </div>
            </div>

            <div class="border rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold">Treasurer Code</h2>
                        <p class="text-sm text-gray-500">Use this code to register as a Treasurer</p>
                    </div>
                </div>
                <div class="mt-3 flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                    <code class="bg-gray-100 px-4 py-2.5 rounded text-base sm:text-lg font-mono select-all break-all text-center sm:text-left">
                        {{ $treasurerCode ?? '— No code set —' }}
                    </code>
                    <form method="POST" action="{{ route('admin.settings.regenerate-treasurer') }}" class="sm:inline">
                        @csrf
                        <button type="submit" class="w-full sm:w-auto bg-blue-600 text-white px-4 py-2.5 rounded hover:bg-blue-700 text-sm"
                            onclick="return confirm('Change Treasurer code? The old code will stop working immediately.')">
                            Regenerate
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded p-4 text-sm text-yellow-800">
            <strong>Note:</strong> When you change a code, the old code stops working immediately. Share the new code with anyone who needs it.
        </div>
    </div>
</div>
@endsection
