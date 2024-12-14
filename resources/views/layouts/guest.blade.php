<!DOCTYPE html>
<html lang="ar" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Al Nada Hosipital') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        <style>
            body {
                background-color: #f0f4f8;
                font-family: 'Amiri', serif;
                color: #333;
                margin: 0;
                padding: 0;
            }
            .container {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                min-height: 100vh;
                padding: 20px;
            }
            .header {
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 30px;
            }
            .logo {
                width: 100px;
                height: auto;
                margin-right: 15px;
            }
            .company-name {
                font-size: 2.5rem;
                font-weight: 700;
                color: #00796b;
            }
            .login-container {
                background-color: #ffffff;
                padding: 40px;
                border-radius: 12px;
                box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
                width: 100%;
                max-width: 400px;
                text-align: center;
            }
            .login-title {
                font-size: 2rem;
                font-weight: 700;
                color: #004d40;
                margin-bottom: 20px;
            }
            .login-form {
                display: flex;
                flex-direction: column;
            }
            .login-form input {
                margin-bottom: 15px;
                padding: 10px;
                font-size: 1rem;
                border: 1px solid #ccc;
                border-radius: 8px;
            }
            .login-form button {
                padding: 10px;
                font-size: 1rem;
                background-color: #00796b;
                color: #fff;
                border: none;
                border-radius: 8px;
                cursor: pointer;
                transition: background-color 0.3s;
            }
            .login-form button:hover {
                background-color: #005f56;
            }
            .error {
                color: #dc3545;
                margin-bottom: 15px;
            }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div class="container">
            <div class="header">
                <img src="{{ asset('logo.png') }}" alt="شعار المستشفى" class="logo">
                <div class="company-name">مستشفى الندى التخصصي</div>
            </div>

            <div class="login-container">
                <h2 class="login-title">تسجيل الدخول إلى حسابك</h2>
                @if ($errors->any())
                    <div class="error">
                        {{ $errors->first() }}
                    </div>
                @endif
                <form class="login-form" method="POST" action="{{ route('login') }}">
                    @csrf
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="البريد الإلكتروني" required autofocus>
                    <input type="password" name="password" placeholder="كلمة المرور" required>
                    <button type="submit">تسجيل الدخول</button>
                </form>
            </div>
        </div>
    </body>
</html>