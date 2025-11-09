<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BillDesk Pro - Hardware Shop Management</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #fafafa;
            color: #1a1a1a;
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .container {
            width: 100%;
            max-width: 400px;
            padding: 2rem;
        }
        
        .logo {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .logo-icon {
            width: 60px;
            height: 60px;
            background: #1a1a1a;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            margin: 0 auto 1rem;
        }
        
        .logo h1 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 0.5rem;
        }
        
        .logo p {
            color: #666;
            font-size: 0.875rem;
        }
        
        .card {
            background: #ffffff;
            border: 1px solid #e5e5e5;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #1a1a1a;
            font-size: 0.875rem;
        }
        
        .form-input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #e5e5e5;
            border-radius: 8px;
            font-size: 0.875rem;
            transition: border-color 0.2s ease;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #1a1a1a;
        }
        
        .btn {
            width: 100%;
            padding: 0.75rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
            margin-bottom: 1rem;
        }
        
        .btn-primary {
            background: #1a1a1a;
            color: #ffffff;
        }
        
        .btn-primary:hover {
            background: #333;
        }
        
        .btn-secondary {
            background: #f8f8f8;
            color: #666;
            border: 1px solid #e5e5e5;
        }
        
        .btn-secondary:hover {
            background: #f0f0f0;
        }
        
        .form-toggle {
            text-align: center;
            margin-top: 1rem;
        }
        
        .form-toggle button {
            background: none;
            border: none;
            color: #1a1a1a;
            cursor: pointer;
            font-size: 0.875rem;
            text-decoration: underline;
        }
        
        .form-toggle button:hover {
            color: #666;
        }
        
        .alert {
            padding: 0.75rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            font-size: 0.875rem;
        }
        
        .alert-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }
        
        .alert-error {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }
        
        .loading {
            text-align: center;
            padding: 1rem;
            color: #666;
        }
        
        .hidden {
            display: none;
        }
        
        .features {
            margin-top: 2rem;
            text-align: center;
        }
        
        .features h3 {
            font-size: 1rem;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 1rem;
        }
        
        .features-list {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.5rem;
            font-size: 0.75rem;
            color: #666;
        }
        
        .feature-item {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
            </style>
    </head>
<body>
    <div class="container">
        <div class="logo">
            <div class="logo-icon">🏪</div>
            <h1>BillDesk Pro</h1>
            <p>Hardware Shop Management System</p>
        </div>

        <div id="alert-container"></div>

        <!-- Login Form -->
        <div id="login-form" class="card">
            <h2 style="text-align: center; margin-bottom: 1.5rem; font-size: 1.25rem; font-weight: 600;">Sign In</h2>
            <form id="loginForm">
                <div class="form-group">
                    <label class="form-label" for="login-email">Email</label>
                    <input type="email" id="login-email" name="email" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="login-password">Password</label>
                    <input type="password" id="login-password" name="password" class="form-input" required>
                </div>
                <button type="submit" class="btn btn-primary">
                    <span>Sign In</span>
                </button>
            </form>
            <div class="form-toggle">
                <button onclick="showRegisterForm()">Don't have an account? Sign up</button>
            </div>
        </div>

        <!-- Register Form -->
        <div id="register-form" class="card hidden">
            <h2 style="text-align: center; margin-bottom: 1.5rem; font-size: 1.25rem; font-weight: 600;">Create Account</h2>
            <form id="registerForm">
                <div class="form-group">
                    <label class="form-label" for="register-name">Full Name</label>
                    <input type="text" id="register-name" name="name" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="register-email">Email</label>
                    <input type="email" id="register-email" name="email" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="register-password">Password</label>
                    <input type="password" id="register-password" name="password" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="register-password-confirm">Confirm Password</label>
                    <input type="password" id="register-password-confirm" name="password_confirmation" class="form-input" required>
                </div>
                <button type="submit" class="btn btn-primary">
                    <span>Create Account</span>
                </button>
            </form>
            <div class="form-toggle">
                <button onclick="showLoginForm()">Already have an account? Sign in</button>
            </div>
        </div>

        <div class="features">
            <h3>Complete Hardware Shop Solution</h3>
            <div class="features-list">
                <div class="feature-item">
                    <span>📦</span>
                    <span>Inventory</span>
                </div>
                <div class="feature-item">
                    <span>🛒</span>
                    <span>Sales</span>
                </div>
                <div class="feature-item">
                    <span>📋</span>
                    <span>Purchases</span>
                </div>
                <div class="feature-item">
                    <span>📄</span>
                    <span>Invoices</span>
                </div>
                <div class="feature-item">
                    <span>💳</span>
                    <span>Billing</span>
                </div>
                <div class="feature-item">
                    <span>👥</span>
                    <span>Users</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Form switching
        function showLoginForm() {
            document.getElementById('login-form').classList.remove('hidden');
            document.getElementById('register-form').classList.add('hidden');
        }

        function showRegisterForm() {
            document.getElementById('login-form').classList.add('hidden');
            document.getElementById('register-form').classList.remove('hidden');
        }

        // Login form submission
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            login();
        });

        // Register form submission
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            e.preventDefault();
            register();
        });

        function login() {
            const formData = new FormData(document.getElementById('loginForm'));
            
            fetch('/api/login', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': token
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.token) {
                    localStorage.setItem('auth_token', data.token);
                    localStorage.setItem('user_data', JSON.stringify(data.user));
                    showAlert('Login successful! Redirecting...', 'success');
                    setTimeout(() => {
                        window.location.href = '/dashboard';
                    }, 1000);
                } else {
                    showAlert('Login failed: ' + (data.message || 'Invalid credentials'), 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Login failed. Please try again.', 'error');
            });
        }

        function register() {
            const formData = new FormData(document.getElementById('registerForm'));
            
            // Check password confirmation
            const password = document.getElementById('register-password').value;
            const passwordConfirm = document.getElementById('register-password-confirm').value;
            
            if (password !== passwordConfirm) {
                showAlert('Passwords do not match', 'error');
                return;
            }
            
            fetch('/api/register', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': token
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Registration successful! Please login.', 'success');
                    showLoginForm();
                    document.getElementById('registerForm').reset();
                } else {
                    showAlert('Registration failed: ' + (data.message || 'Please check your input'), 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Registration failed. Please try again.', 'error');
            });
        }

        function showAlert(message, type) {
            const container = document.getElementById('alert-container');
            const alertClass = type === 'success' ? 'alert-success' : 'alert-error';
            
            container.innerHTML = `<div class="alert ${alertClass}">${message}</div>`;
            
            setTimeout(() => {
                container.innerHTML = '';
            }, 5000);
        }

        // Check if user is already logged in
        document.addEventListener('DOMContentLoaded', function() {
            const authToken = localStorage.getItem('auth_token');
            if (authToken) {
                // Verify token is still valid
                fetch('/api/profile', {
                    method: 'GET',
                    headers: {
                        'Authorization': 'Bearer ' + authToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        window.location.href = '/dashboard';
                    } else {
                        localStorage.removeItem('auth_token');
                        localStorage.removeItem('user_data');
                    }
                })
                .catch(() => {
                    localStorage.removeItem('auth_token');
                    localStorage.removeItem('user_data');
                });
            }
        });
    </script>
    </body>
</html>