<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password – {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="bg-white shadow-md rounded-lg p-8 w-full max-w-md">

        {{-- Logo / Title --}}
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">{{ config('app.name') }}</h1>
            <p class="text-gray-500 mt-1 text-sm">Forgot your password?</p>
            <p class="text-gray-400 text-xs mt-1">
                No problem. Enter your email and we'll send you a reset link.
            </p>
        </div>

        {{-- Success Status --}}
        @if (session('status'))
            <div class="bg-green-100 text-green-700 border border-green-300 rounded px-4 py-3 mb-4 text-sm">
                {{ session('status') }}
            </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            {{-- Email --}}
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                    Email Address
                </label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                    placeholder="you@example.com"
                >
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Submit --}}
            <button
                type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded text-sm transition"
            >
                Send Password Reset Link
            </button>
        </form>

        {{-- Back to Login --}}
        <div class="text-center mt-5">
            <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:underline">
                &larr; Back to Login
            </a>
        </div>

    </div>

</body>
</html>