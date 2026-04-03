<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Plantsware</title>
     <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/fav-icon.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(rgba(0, 0, 0, 0.45), rgba(0, 0, 0, 0.45)), 
                        url('{{ asset('assets/images/login-bg.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            margin: 0;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 50px 40px;
            max-width: 450px;
            width: 90%;
            transition: all 0.3s ease;
        }
        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }
        .login-header h2 {
            color: #134e5e;
            font-weight: 700;
            letter-spacing: -0.5px;
            margin-top: 10px;
        }
        .form-label {
            font-weight: 600;
            color: #444;
            margin-bottom: 8px;
        }
        .input-group-text {
            background-color: #f8f9fa;
            border-right: none;
            color: #134e5e;
        }
        .form-control {
            border-left: none;
            padding: 12px;
            border-radius: 0 8px 8px 0;
        }
        .form-control:focus {
            border-color: #71b280;
            box-shadow: 0 0 0 0.25rem rgba(113, 178, 128, 0.2);
        }
        .btn-login {
            background: linear-gradient(135deg, #134e5e 0%, #71b280 100%);
            border: none;
            padding: 14px;
            font-weight: 700;
            font-size: 1.1rem;
            width: 100%;
            border-radius: 12px;
            margin-top: 10px;
            color: white;
            transition: all 0.3s ease;
        }
        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(19, 78, 94, 0.3);
            background: linear-gradient(135deg, #0f3d4a 0%, #5a8e66 100%);
        }
        .input-group .btn-outline-secondary {
            border-left: none;
            background: white;
            color: #666;
            border-color: #dee2e6;
        }
        /* Hide browser-default password reveal icons */
        ::-ms-reveal, ::-ms-clear {
            display: none !important;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
             @php $headerFooter = \App\Models\HeaderFooter::first(); @endphp
                     <img src="{{ asset('assets/images/logo-1.png') }}" alt="Plantly Logo" style="background-color: #fff; border: 1px solid #bd1313ff; border-radius: 10px; margin-bottom: 15px;height: 13vh;width: 14vw;object-fit: cover;">
            <!-- <p class="text-muted">Plantsware Admin Panel</p> -->
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}">
            @csrf
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}" required autofocus>
                </div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" class="form-control" id="password" name="password" required>
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label" for="remember">Remember me</label>
            </div>

            <button type="submit" class="btn btn-primary btn-login">
                <i class="fas fa-sign-in-alt"></i> Login
            </button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('togglePassword').addEventListener('click', function (e) {
            const password = document.getElementById('password');
            const icon = this.querySelector('i');
            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                password.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    </script>
</body>
</html>

