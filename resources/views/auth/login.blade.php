<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — Tread CRM</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: #1a1a2e; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem; }
        .logo-tile { width: 52px; height: 52px; background: #7C3AED; border-radius: 14px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; }
        .card { background: #fff; border-radius: 16px; padding: 2rem; width: 100%; max-width: 420px; }
        .field-label { font-size: 11px; font-weight: 600; color: #555; text-transform: uppercase; letter-spacing: 0.06em; display: block; margin-bottom: 6px; }
        .input-wrap { position: relative; margin-bottom: 1rem; }
        .input-wrap svg { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); pointer-events: none; }
        input[type=email], input[type=password] { width: 100%; padding: 10px 12px 10px 36px; border: 1px solid #e2e2e2; border-radius: 8px; font-size: 14px; font-family: inherit; background: #fafafa; color: #111; outline: none; transition: border 0.2s; }
        input:focus { border-color: #7C3AED; }
        .row-label { display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; }
        .forgot { font-size: 12px; color: #7C3AED; text-decoration: none; }
        .btn-primary { width: 100%; padding: 11px; background: #7C3AED; color: #fff; border: none; border-radius: 8px; font-size: 14px; font-weight: 500; font-family: inherit; cursor: pointer; letter-spacing: 0.01em; transition: background 0.2s; }
        .btn-primary:hover { background: #6D28D9; }
        .demo-box { margin-top: 1.5rem; padding: 12px; background: #f7f5ff; border-radius: 8px; border: 1px solid #ede9fe; }
        .demo-box p:first-child { font-size: 11px; font-weight: 600; color: #6D28D9; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 4px; }
        .demo-box p:last-child { font-size: 12px; color: #7C3AED; font-family: monospace; }
        .footer { text-align: center; font-size: 12px; color: rgba(255,255,255,0.3); margin-top: 1.5rem; }
    </style>
</head>
<body>
<div style="width:100%;max-width:420px;">
    <div style="text-align:center;margin-bottom:2rem;">
        <div class="logo-tile">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
        </div>
        <p style="font-size:22px;font-weight:500;color:#fff;margin-bottom:4px;">Tread CRM</p>
        <p style="font-size:13px;color:rgba(255,255,255,0.45);">Admin Dashboard</p>
    </div>

    <div class="card">
        <p style="font-size:18px;font-weight:500;color:#111;margin-bottom:1.5rem;">Sign in to your account</p>

        <form method="POST" action="{{ route('login.store') }}">
            @csrf
            <label class="field-label">Email address</label>
            <div class="input-wrap">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#aaa" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M2 7l10 7 10-7"/></svg>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="admin@tread-crm.com" required autocomplete="email" class="@error('email') is-invalid @enderror">
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="row-label">
                <label class="field-label" style="margin-bottom:0;">Password</label>
                <a href="#" class="forgot">Forgot password?</a>
            </div>
            <div class="input-wrap">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#aaa" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                <input type="password" name="password" placeholder="••••••••" required autocomplete="current-password" class="@error('password') is-invalid @enderror">
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div style="display:flex;align-items:center;gap:8px;margin-bottom:1.5rem;">
                <input type="checkbox" name="remember" id="remember" style="accent-color:#7C3AED;width:16px;height:16px;">
                <label for="remember" style="font-size:13px;color:#666;">Keep me signed in for 30 days</label>
            </div>

            <button type="submit" class="btn-primary">Sign in</button>
        </form>

        <div class="demo-box">
            <p>Demo credentials</p>
            <p>admin@tread-crm.com &nbsp;/&nbsp; password</p>
        </div>
    </div>

    <p class="footer">&copy; 2026 Tread CRM. All rights reserved.</p>
</div>
</body>
</html>