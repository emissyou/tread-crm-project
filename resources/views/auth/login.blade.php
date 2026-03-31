<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Tread CRM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Same styles as master layout */
        :root { --tread-purple: #6f42c1; --tread-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        body { font-family: 'Poppins', sans-serif; background: var(--tread-gradient); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-card { background: rgba(255,255,255,0.95); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.2); box-shadow: 0 25px 45px rgba(0,0,0,0.1); }
        .btn-tread { background: var(--tread-gradient); border: none; border-radius: 50px; font-weight: 600; padding: 12px 30px; transition: all 0.3s ease; }
        .btn-tread:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(102,126,234,0.4); }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5 col-xl-4">
                <div class="text-center mb-5">
                    <div class="bg-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4 shadow-lg" 
                         style="width: 120px; height: 120px;">
                        <i class="fas fa-chart-line fa-3x text-primary"></i>
                    </div>
                    <h1 class="h2 fw-bold mb-3 text-white">Tread CRM</h1>
                    <p class="text-white-50 mb-0 fs-6">Admin Dashboard Access</p>
                </div>

                <div class="card login-card p-4 p-md-5">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-dark">Email</label>
                            <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                   name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="admin@tread-crm.com">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-dark">Password</label>
                            <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                   name="password" required autocomplete="current-password" placeholder="••••••••">
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-4 form-check">
                            <input type="checkbox" class="form-check-input" name="remember" id="remember">
                            <label class="form-check-label text-muted" for="remember">Remember me</label>
                        </div>
                        <button type="submit" class="btn btn-tread btn-lg w-100 text-white mb-3">
                            <i class="fas fa-sign-in-alt me-2"></i>Sign In
                        </button>
                    </form>
                    <div class="text-center pt-3">
                        <small class="text-muted">
                            👑 <strong>admin@tread-crm.com</strong> / <strong>password</strong>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>