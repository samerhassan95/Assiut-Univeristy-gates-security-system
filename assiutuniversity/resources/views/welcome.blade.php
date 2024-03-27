<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            text-align: center;
            margin-top: 150px;
        }
        h1 {
            color: #3490dc;
            margin-bottom: 20px;
        }
        p {
            color: #555;
            margin-bottom: 30px;
        }
        .login-btn, .register-btn {
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .login-btn {
            background-color: #3490dc;
            color: white;
            margin-right: 10px;
        }
        .register-btn {
            background-color: #38c172;
            color: white;
        }
        .login-btn:hover, .register-btn:hover {
            filter: brightness(90%);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>مرحباً بك في جامعة أسيوط</h1>
        <p>من فضلك قم بتسجيل الدخول او إنشاء حساب لإستخدام لوحة التحكم</p>
        <a href="{{ route('login') }}" class="login-btn">تسجيل الدخول</a>
        <a href="{{ route('register') }}" class="register-btn">إنشاء حساب</a>
        
        <!-- Replace href with your actual login and register routes -->
    </div>
</body>
</html>
