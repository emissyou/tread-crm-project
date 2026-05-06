<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — Tread CRM</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Syne:wght@700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:       #04080f;
            --card:     #ffffff;
            --accent:   #2563eb;
            --accent-h: #1d4ed8;
            --text:     #0f172a;
            --sub:      #64748b;
            --border:   #e2e8f0;
            --input-bg: #f8fafc;
            --ring:     rgba(37,99,235,0.14);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            overflow: hidden;
            position: relative;
        }

        /* ── Background layers ── */
        .bg-mesh {
            position: fixed;
            inset: 0;
            z-index: 0;
            background-image:
                radial-gradient(ellipse 60% 50% at 20% 40%, rgba(37,99,235,0.12) 0%, transparent 70%),
                radial-gradient(ellipse 40% 40% at 75% 70%, rgba(124,58,237,0.07) 0%, transparent 60%);
        }
        .bg-dots {
            position: fixed;
            inset: 0;
            z-index: 0;
            background-image: radial-gradient(circle, rgba(255,255,255,0.045) 1px, transparent 1px);
            background-size: 32px 32px;
        }

        /* ── Wrapper ── */
        .wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 460px;
            animation: rise 0.55s cubic-bezier(0.22, 1, 0.36, 1) both;
        }
        @keyframes rise {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Brand ── */
        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 2.25rem;
            justify-content: center;
        }
        .brand-mark {
            width: 42px; height: 42px;
            border-radius: 11px;
            background: var(--accent);
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 18px rgba(37,99,235,0.45);
            flex-shrink: 0;
        }
        .brand-wordmark {
            font-family: 'Syne', sans-serif;
            font-size: 1.25rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.02em;
            line-height: 1;
        }
        .brand-wordmark span { color: rgba(255,255,255,0.38); font-weight: 700; }

        /* ── Card ── */
        .card {
            background: var(--card);
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow:
                0 0 0 1px rgba(255,255,255,0.05),
                0 24px 60px rgba(0,0,0,0.55),
                0 6px 18px rgba(0,0,0,0.3);
        }

        /* ── Card heading ── */
        .card-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #eff6ff;
            color: var(--accent);
            border-radius: 999px;
            padding: 4px 12px;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 1rem;
        }
        .card-eyebrow-dot {
            width: 5px; height: 5px;
            border-radius: 50%;
            background: var(--accent);
        }
        .card-title {
            font-family: 'Syne', sans-serif;
            font-size: 1.65rem;
            font-weight: 800;
            color: var(--text);
            letter-spacing: -0.03em;
            line-height: 1.15;
            margin-bottom: 6px;
        }
        .card-sub {
            font-size: 0.875rem;
            color: var(--sub);
            font-weight: 400;
            margin-bottom: 2rem;
            line-height: 1.5;
        }

        /* ── Error alert ── */
        .alert-error {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 12px 14px;
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 10px;
            margin-bottom: 1.25rem;
            font-size: 0.845rem;
            color: #b91c1c;
            font-weight: 500;
            line-height: 1.4;
        }
        .alert-error svg { flex-shrink: 0; margin-top: 1px; }

        /* ── Field ── */
        .field { margin-bottom: 1.1rem; }
        .field-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 7px;
        }
        .field-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: #374151;
            letter-spacing: 0.01em;
        }
        .forgot {
            font-size: 0.78rem;
            color: var(--accent);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.15s;
        }
        .forgot:hover { color: var(--accent-h); text-decoration: underline; }

        /* Input */
        .input-wrap { position: relative; }
        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            color: #94a3b8;
            display: flex; align-items: center;
        }
        .input-wrap input {
            width: 100%;
            height: 48px;
            padding: 0 44px 0 42px;
            border: 1.5px solid var(--border);
            border-radius: 11px;
            font-size: 0.915rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 500;
            background: var(--input-bg);
            color: var(--text);
            outline: none;
            transition: border-color 0.18s, box-shadow 0.18s, background 0.18s;
            -webkit-appearance: none;
        }
        .input-wrap input:focus {
            border-color: var(--accent);
            background: #fff;
            box-shadow: 0 0 0 4px var(--ring);
        }
        .input-wrap input::placeholder {
            color: #cbd5e1;
            font-weight: 400;
        }
        .input-wrap input.is-invalid {
            border-color: #ef4444;
            box-shadow: 0 0 0 4px rgba(239,68,68,0.1);
        }
        .invalid-feedback {
            font-size: 0.775rem;
            color: #dc2626;
            margin-top: 5px;
            font-weight: 500;
        }

        /* Password toggle */
        .pwd-toggle {
            position: absolute;
            right: 13px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #94a3b8;
            padding: 4px;
            border-radius: 6px;
            display: flex; align-items: center;
            transition: color 0.15s, background 0.15s;
        }
        .pwd-toggle:hover { color: var(--accent); background: #eff6ff; }

        /* Remember me */
        .remember-row {
            display: flex;
            align-items: center;
            gap: 9px;
            margin-bottom: 1.6rem;
            margin-top: 0.25rem;
        }
        .remember-row input[type="checkbox"] {
            width: 16px; height: 16px;
            accent-color: var(--accent);
            cursor: pointer;
            flex-shrink: 0;
        }
        .remember-row label {
            font-size: 0.855rem;
            color: var(--sub);
            cursor: pointer;
            user-select: none;
            font-weight: 500;
        }

        /* Submit */
        .btn-submit {
            width: 100%;
            height: 50px;
            background: var(--accent);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 700;
            font-family: 'Plus Jakarta Sans', sans-serif;
            cursor: pointer;
            letter-spacing: 0.01em;
            transition: background 0.18s, transform 0.18s, box-shadow 0.18s;
            box-shadow: 0 4px 16px rgba(37,99,235,0.35);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-submit:hover {
            background: var(--accent-h);
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(37,99,235,0.4);
        }
        .btn-submit:active { transform: translateY(0); box-shadow: none; }
        .btn-arrow { transition: transform 0.2s; }
        .btn-submit:hover .btn-arrow { transform: translateX(3px); }

        /* ── Security note ── */
        .security-note {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin-top: 1.4rem;
            font-size: 0.75rem;
            color: #94a3b8;
            font-weight: 500;
        }
        .security-note svg { opacity: 0.6; }

        /* ── Footer ── */
        .footer {
            text-align: center;
            font-size: 0.72rem;
            color: rgba(255,255,255,0.2);
            margin-top: 1.75rem;
            font-weight: 500;
            letter-spacing: 0.02em;
        }
    </style>
</head>
<body>

<div class="bg-mesh"></div>
<div class="bg-dots"></div>

<div class="wrapper">

    <!-- Brand -->
    <div class="brand">
        <div class="brand-mark">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
            </svg>
        </div>
        <div class="brand-wordmark">Tread<span>CRM</span></div>
    </div>

    <!-- Card -->
    <div class="card">

        <div class="card-eyebrow">
            <div class="card-eyebrow-dot"></div>
            Secure Access
        </div>

        <div class="card-title">Welcome back</div>
        <div class="card-sub">Sign in to your workspace to continue.</div>

        @if(session('error'))
        <div class="alert-error">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            {{ session('error') }}
        </div>
        @endif

        <form method="POST" action="{{ route('login.store') }}">
            @csrf

            <!-- Email -->
            <div class="field">
                <div class="field-header">
                    <label class="field-label" for="email">Email address</label>
                </div>
                <div class="input-wrap">
                    <span class="input-icon">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="4" width="20" height="16" rx="2"/><path d="M2 7l10 7 10-7"/>
                        </svg>
                    </span>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="you@company.com"
                        required
                        autocomplete="email"
                        class="@error('email') is-invalid @enderror"
                    >
                </div>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <!-- Password -->
            <div class="field">
                <div class="field-header">
                    <label class="field-label" for="password">Password</label>
                    <a href="{{ route('password.request') }}" class="forgot">Forgot password?</a>
                </div>
                <div class="input-wrap">
                    <span class="input-icon">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                    </span>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="••••••••"
                        required
                        autocomplete="current-password"
                        class="@error('password') is-invalid @enderror"
                    >
                    <button type="button" class="pwd-toggle" onclick="togglePwd()" title="Show/hide password" tabindex="-1">
                        <svg id="eyeIcon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <!-- Remember me -->
            <div class="remember-row">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember">Keep me signed in for 30 days</label>
            </div>

            <button type="submit" class="btn-submit">
                Sign in
                <svg class="btn-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                </svg>
            </button>
        </form>

        <div class="security-note">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            </svg>
            Secured with 256-bit encryption
        </div>

    </div>

    <p class="footer">&copy; 2026 Tread CRM &nbsp;·&nbsp; All rights reserved.</p>

</div>

<script>
function togglePwd() {
    const input = document.getElementById('password');
    const icon  = document.getElementById('eyeIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.innerHTML = `<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/>`;
    } else {
        input.type = 'password';
        icon.innerHTML = `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>`;
    }
}
</script>
</body>
</html>