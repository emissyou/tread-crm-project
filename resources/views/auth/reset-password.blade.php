<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password – {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="bg-white shadow-md rounded-lg p-8 w-full max-w-md">

        {{-- Logo / Title --}}
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">{{ config('app.name') }}</h1>
            <p class="text-gray-500 mt-1 text-sm">Reset your password</p>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            {{-- Token (hidden) --}}
            <input type="hidden" name="token" value="{{ $token }}">

            {{-- Email --}}
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                    Email Address
                </label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ $email ?? old('email') }}"
                    required
                    autofocus
                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                    placeholder="you@example.com"
                >
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- New Password --}}
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                    New Password
                </label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror"
                    placeholder="Minimum 8 characters"
                >
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div class="mb-6">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                    Confirm New Password
                </label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    required
                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Repeat your new password"
                >
            </div>

            {{-- Submit --}}
            <button
                type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded text-sm transition"
            >
                Reset Password
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