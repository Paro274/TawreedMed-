<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل دخول الأدمن</title>

    <style>
        body {
            font-family: "Cairo", sans-serif;
            background: linear-gradient(135deg, #4f46e5, #9333ea);
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #333;
        }

        .login-container {
            background-color: #fff;
            padding: 40px 35px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            width: 100%;
            max-width: 380px;
            text-align: center;
        }

        .login-container h2 {
            margin-bottom: 25px;
            color: #4f46e5;
            font-size: 22px;
        }

        .login-container label {
            display: block;
            text-align: right;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }

        .login-container input {
            width: 100%;
            padding: 10px 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .login-container input:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 6px rgba(79, 70, 229, 0.3);
            outline: none;
        }

        .login-container button {
            width: 100%;
            background-color: #4f46e5;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .login-container button:hover {
            background-color: #4338ca;
        }

        .error-message {
            color: #e11d48;
            background: rgba(255, 228, 230, 0.8);
            padding: 8px;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        @media (max-width: 450px) {
            .login-container {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>تسجيل دخول الأدمن</h2>
        
        @if($errors->has('login_error'))
            <div class="error-message">{{ $errors->first('login_error') }}</div>
        @endif

        <form method="POST" action="/admin/login">
            @csrf
            <label for="username">اسم المستخدم</label>
            <input type="text" id="username" name="username" placeholder="أدخل اسم المستخدم" required>

            <label for="password">كلمة المرور</label>
            <input type="password" id="password" name="password" placeholder="أدخل كلمة المرور" required>

            <button type="submit">دخول</button>
        </form>
    </div>
</body>
</html>
