<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>لوحة تحكم المرضى</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #87ceeb; /* Sky blue color */
            --secondary-color: #00bfff; /* Deep sky blue color */
            --background-color: #f0f8ff; /* Alice blue */
            --text-color: #4b0082; /* Indigo */
            --card-background: #ffffff;
            --sidebar-width: 250px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
            display: flex;
            min-height: 100vh;
            direction: rtl;
        }

        .sidebar {
            background-color: var(--primary-color);
            padding: 2rem;
            color: white;
            height: 100vh;
            position: fixed;
            top: 0;
            right: 0;
            width: var(--sidebar-width);
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .logo {
            width: 100%;
            max-width: 150px;
            margin-bottom: 1rem;
        }

        .company-name {
            font-size: 1.5rem;
            margin-bottom: 2rem;
            font-weight: bold;
        }

        .sidebar h2 {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
            width: 100%;
        }

        .sidebar ul li {
            margin: 1rem 0;
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
            font-size: 1.1rem;
            transition: background-color 0.3s;
            display: flex;
            align-items: center;
            padding: 0.5rem 1rem;
            border-radius: 5px;
        }

        .sidebar ul li a:hover {
            background-color: var(--secondary-color);
        }

        .sidebar ul li a i {
            margin-left: 0.5rem;
        }

        .main {
            flex: 1;
            padding: 2rem;
            margin-right: var(--sidebar-width);
            overflow-y: auto;
        }

        .card {
            background-color: var(--card-background);
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        h2 {
            margin-bottom: 1.5rem;
            color: var(--primary-color);
            font-size: 1.8rem;
        }

        .patient-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .patient-table th,
        .patient-table td {
            padding: 1rem;
            text-align: right;
        }

        .patient-table th {
            background-color: var(--primary-color);
            color: white;
            font-weight: 500;
        }

        .patient-table tr:nth-child(even) {
            background-color: #e0ffff; /* Light cyan */
        }

        .patient-table tr:hover {
            background-color: #b0e0e6; /* Powder blue */
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 1rem;
        }

        .pagination a {
            color: var(--primary-color);
            padding: 0.5rem 1rem;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .pagination a:hover {
            background-color: var(--secondary-color);
            color: white;
        }

        button {
            background-color: var(--secondary-color);
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-size: 1rem;
            font-family: 'Cairo', sans-serif;
        }

        button:hover {
            background-color: #87ceeb;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: var(--card-background);
            margin: 10% auto;
            padding: 2rem;
            border-radius: 10px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .close {
            color: #aaa;
            float: left;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: #000;
        }

        .error {
            color: #e74c3c;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }

        .input-group {
            margin-bottom: 1rem;
        }

        .input-group input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            font-family: 'Cairo', sans-serif;
        }

        @media (max-width: 768px) {
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
                padding: 1rem;
            }

            .main {
                margin-right: 0;
                padding: 1rem;
            }

            .card {
                padding: 1rem;
            }

            h2 {
                font-size: 1.5rem;
            }

            button {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }

            .input-group input {
                padding: 0.5rem;
                font-size: 0.9rem;
            }

            .patient-table th,
            .patient-table td {
                padding: 0.5rem;
                font-size: 0.9rem;
            }

            .pagination a {
                padding: 0.25rem 0.5rem;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 480px) {
            .sidebar {
                padding: 0.5rem;
            }

            .main {
                padding: 0.5rem;
            }

            .card {
                padding: 0.5rem;
            }

            h2 {
                font-size: 1.2rem;
            }

            button {
                padding: 0.25rem 0.5rem;
                font-size: 0.8rem;
            }

            .input-group input {
                padding: 0.25rem;
                font-size: 0.8rem;
            }

            .patient-table th,
            .patient-table td {
                padding: 0.25rem;
                font-size: 0.8rem;
            }

            .pagination a {
                padding: 0.1rem 0.25rem;
                font-size: 0.8rem;
            }
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<div class="sidebar">
    <img src="logo.png" alt="الشعار" class="logo">
    <div class="company-name">مستشفى الندى التخصصى</div>
    <hr style="border: 0; height: 1px; background-image: linear-gradient(to right, rgba(255, 255, 255, 0), rgba(255, 255, 255, 0.75), rgba(255, 255, 255, 0)); margin: 20px 0;">
    <ul>
        <li><a href="/dashboard"><i class="fas fa-tachometer-alt"></i> لوحة التحكم</a></li>
        <li><a href="/patients"><i class="fas fa-user-injured"></i> المرضى</a></li>
        <li><a href="/visits"><i class="far fa-calendar-alt"></i> الزيارات</a></li>
{{--        <li><a href="/reports"><i class="fas fa-chart-bar"></i> التقارير</a></li>--}}
{{--        <li><a href="/settings"><i class="fas fa-cog"></i> الإعدادات</a></li>--}}
        <li><a href="/logout"><i class="fas fa-sign-out-alt"></i> تسجيل الخروج</a></li>
    </ul>
</div>
<div class="main">
    @yield('content')
</div>
</body>
</html>
