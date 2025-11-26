<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SOLECO | Admin Login</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            height: 100%;
            overflow: hidden;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #0F172A 0%, #1E293B 50%, #334155 100%);
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        /* Animated background elements */
        body::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at 20% 80%, rgba(255, 165, 0, 0.2) 0%, transparent 50%),
                        radial-gradient(circle at 80% 20%, rgba(255, 140, 0, 0.15) 0%, transparent 50%),
                        radial-gradient(circle at 40% 40%, rgba(255, 193, 7, 0.1) 0%, transparent 50%);
            animation: float 20s ease-in-out infinite;
            z-index: 0;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(30px, -30px) rotate(120deg); }
            66% { transform: translate(-20px, 20px) rotate(240deg); }
        }

        .login-container {
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.4), 
                        0 0 0 1px rgba(255, 255, 255, 0.05),
                        inset 0 1px 0 rgba(255, 255, 255, 0.1);
            overflow: hidden;
            max-width: 900px;
            width: 100%;
            display: flex;
            min-height: 600px;
            position: relative;
            z-index: 1;
            animation: slideUp 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-left {
            flex: 1;
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.8) 0%, rgba(15, 23, 42, 0.6) 100%);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 3rem;
            position: relative;
            border-right: 1px solid rgba(255, 255, 255, 0.1);
        }

        .login-left::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="20" height="20" patternUnits="userSpaceOnUse"><path d="M 20 0 L 0 0 0 20" fill="none" stroke="%23475569" stroke-width="0.5" opacity="0.3"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            opacity: 0.4;
        }

        .admin-illustration {
            position: relative;
            z-index: 1;
            text-align: center;
            animation: fadeInUp 1s ease-out 0.3s both;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .admin-icon {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #FFA500 0%, #FF8C00 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            box-shadow: 0 20px 40px rgba(255, 165, 0, 0.4),
                        0 0 0 1px rgba(255, 255, 255, 0.1),
                        inset 0 1px 0 rgba(255, 255, 255, 0.2);
            position: relative;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .admin-icon::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(135deg, #FFA500, #FF8C00, #FFD700);
            border-radius: 50%;
            z-index: -1;
            animation: rotate 3s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .admin-icon i {
            font-size: 3rem;
            color: white;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .illustration-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: #F8FAFC;
            margin-bottom: 0.75rem;
            letter-spacing: -0.02em;
        }

        .illustration-subtitle {
            color: #94A3B8;
            font-size: 1.1rem;
            font-weight: 400;
            line-height: 1.5;
            max-width: 280px;
        }

        .login-right {
            flex: 1;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: rgba(30, 41, 59, 0.6);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .login-header {
            text-align: center;
            margin-bottom: 2.5rem;
            animation: fadeInUp 1s ease-out 0.5s both;
        }

        .login-title {
            font-size: 2rem;
            font-weight: 800;
            color: #F8FAFC;
            margin-bottom: 0.75rem;
            letter-spacing: -0.02em;
        }

        .login-subtitle {
            color: #94A3B8;
            font-size: 1.1rem;
            font-weight: 400;
            line-height: 1.5;
        }

        .form-group {
            margin-bottom: 1.5rem;
            animation: fadeInUp 1s ease-out 0.7s both;
        }

        .form-group:nth-child(2) {
            animation-delay: 0.8s;
        }

        .form-group:nth-child(3) {
            animation-delay: 0.9s;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #E2E8F0;
            margin-bottom: 0.75rem;
            font-size: 0.95rem;
            letter-spacing: 0.01em;
        }

        .form-input {
            width: 100%;
            padding: 1rem 1.25rem;
            border: 2px solid rgba(71, 85, 105, 0.6);
            border-radius: 16px;
            font-size: 1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            color: #F8FAFC;
        }

        .form-input::placeholder {
            color: #94A3B8;
            font-weight: 400;
        }

        .form-input:focus {
            outline: none;
            border-color: #FFA500;
            background: rgba(15, 23, 42, 1);
            box-shadow: 0 0 0 4px rgba(255, 165, 0, 0.2),
                        0 4px 12px rgba(255, 165, 0, 0.3);
            transform: translateY(-1px);
        }

        .form-input.error {
            border-color: #EF4444;
            background: rgba(127, 29, 29, 0.8);
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.2);
        }

        .error-message {
            color: #EF4444;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            font-weight: 500;
        }

        .login-btn {
            width: 100%;
            background: linear-gradient(135deg, #FFA500 0%, #FF8C00 100%);
            color: white;
            border: none;
            padding: 1rem 1.5rem;
            border-radius: 16px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            margin-top: 1.5rem;
            position: relative;
            overflow: hidden;
            animation: fadeInUp 1s ease-out 1s both;
        }

        .login-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .login-btn:hover::before {
            left: 100%;
        }

        .login-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(255, 165, 0, 0.4),
                        0 5px 15px rgba(255, 165, 0, 0.2);
        }

        .login-btn:active {
            transform: translateY(-1px);
        }

        .login-btn i {
            font-size: 1.1rem;
        }

        .alert {
            padding: 1.25rem;
            border-radius: 16px;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
            font-weight: 500;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            animation: fadeInUp 1s ease-out 0.6s both;
        }

        .alert-danger {
            background: rgba(127, 29, 29, 0.8);
            color: #FCA5A5;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .footer {
            margin-top: 3rem;
            text-align: center;
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.95rem;
            font-weight: 500;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            animation: fadeInUp 1s ease-out 1.2s both;
        }

        /* Enhanced Responsive Design */
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                max-width: 400px;
                min-height: auto;
                border-radius: 20px;
            }

            .login-left {
                padding: 2rem;
                border-right: none;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }

            .login-right {
                padding: 2rem;
            }

            .admin-icon {
                width: 90px;
                height: 90px;
            }

            .admin-icon i {
                font-size: 2.25rem;
            }

            .illustration-title {
                font-size: 1.3rem;
            }

            .login-title {
                font-size: 1.75rem;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 15px;
            }

            .login-left,
            .login-right {
                padding: 1.5rem;
            }

            .login-title {
                font-size: 1.5rem;
            }

            .illustration-title {
                font-size: 1.2rem;
            }
        }

        /* Loading animation for button */
        .login-btn.loading {
            pointer-events: none;
            opacity: 0.8;
        }

        .login-btn.loading i {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Left Side - Illustration -->
        <div class="login-left">
            <div class="admin-illustration">
                <div class="admin-icon">
                    <i class="fas fa-user-shield"></i>
                </div>
                <h2 class="illustration-title">System Administrator</h2>
                <p class="illustration-subtitle">Secure access to Energy Grid Monitoring System</p>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="login-right">
                <div class="login-header">
                    <h1 class="login-title">Admin Login</h1>
                    <p class="login-subtitle">Enter your credentials to manage the system</p>
                </div>

            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.submit') }}">
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           class="form-input @error('email') error @enderror" 
                           value="{{ old('email') }}" 
                           required 
                           autofocus
                           placeholder="Enter your email address">
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="form-input @error('password') error @enderror" 
                           required
                           placeholder="Enter your password">
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="login-btn">
                    <i class="fas fa-sign-in-alt"></i>
                    Login
                </button>
            </form>
        </div>
    </div>

    <footer class="footer">
        © 2025 Southern Leyte Electric Cooperative (SOLECO). All Rights Reserved.
    </footer>

    <script>
        // Prevent scrolling
        document.addEventListener('wheel', function(e) {
            e.preventDefault();
        }, { passive: false });

        document.addEventListener('touchmove', function(e) {
            e.preventDefault();
        }, { passive: false });

        document.addEventListener('keydown', function(e) {
            // Prevent arrow keys, page up/down, home, end from scrolling
            if([32, 33, 34, 35, 36, 37, 38, 39, 40].indexOf(e.keyCode) > -1) {
                e.preventDefault();
            }
        });

        // Enhanced form interactions
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const loginBtn = document.querySelector('.login-btn');
            const inputs = document.querySelectorAll('.form-input');

            // Add loading state to button on form submit
            if (form) {
                form.addEventListener('submit', function() {
                    loginBtn.classList.add('loading');
                    loginBtn.innerHTML = '<i class="fas fa-spinner"></i> Signing in...';
                });
            }

            // Enhanced input focus effects
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'translateY(-2px)';
                });

                input.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'translateY(0)';
                });

                // Real-time validation feedback
                input.addEventListener('input', function() {
                    if (this.value.trim() !== '') {
                        this.style.borderColor = '#FFA500';
                        this.style.background = 'rgba(255, 165, 0, 0.1)';
                    } else {
                        this.style.borderColor = 'rgba(71, 85, 105, 0.6)';
                        this.style.background = 'rgba(15, 23, 42, 0.8)';
                    }
                });
            });

            // Add subtle parallax effect to background
            let mouseX = 0, mouseY = 0;
            document.addEventListener('mousemove', function(e) {
                mouseX = (e.clientX / window.innerWidth) * 100;
                mouseY = (e.clientY / window.innerHeight) * 100;
                
                const loginContainer = document.querySelector('.login-container');
                if (loginContainer) {
                    loginContainer.style.transform = `translate(${mouseX * 0.02}px, ${mouseY * 0.02}px)`;
                }
            });

            // Add keyboard navigation enhancement
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && e.target.tagName !== 'BUTTON') {
                    const inputs = Array.from(document.querySelectorAll('.form-input'));
                    const currentIndex = inputs.indexOf(e.target);
                    if (currentIndex < inputs.length - 1) {
                        inputs[currentIndex + 1].focus();
                    } else {
                        loginBtn.click();
                    }
                }
            });
        });
    </script>
</body>
</html>
