<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Tread CRM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full space-y-8">
        <!-- Header -->
        <div class="text-center">
            <div class="mx-auto h-20 w-20 bg-gradient-to-r from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center shadow-2xl mb-6">
                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h2 class="text-4xl font-bold bg-gradient-to-r from-white to-gray-200 bg-clip-text text-transparent mb-2">Tread CRM</h2>
            <p class="text-gray-400 text-sm">Join Admin Dashboard</p>
        </div>

        <!-- Register Form -->
        <form method="POST" action="{{ route('register') }}" class="bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20 shadow-2xl">
            @csrf

            <!-- Name -->
            <div class="space-y-2 mb-6">
                <input type="text" name="name" value="{{ old('name') }}" required autocomplete="name" placeholder="Full Name" 
                       class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-2xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>

            <!-- Email -->
            <div class="space-y-2 mb-6">
                <input type="email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email" 
                       class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-2xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>

            <!-- Password -->
            <div class="space-y-2 mb-6">
                <input type="password" name="password" required autocomplete="new-password" placeholder="Password" 
                       class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-2xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>

            <!-- Confirm Password -->
            <div class="space-y-2 mb-6">
                <input type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password" 
                       class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-2xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>

            <!-- Role (Hidden for Admin) -->
            <input type="hidden" name="role" value="user">

            <button type="submit" 
                    class="w-full bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-semibold py-4 px-6 rounded-2xl shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300">
                Create Account
            </button>
        </form>
    </div>
</body>
</html>