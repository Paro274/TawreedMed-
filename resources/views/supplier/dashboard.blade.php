<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم المورد</title>
    <style>
        body {
            font-family: "Cairo", sans-serif;
            background: #f4f6fc;
            margin: 0;
            padding: 0;
        }

        .content {
            margin-right: 240px;
            padding: 30px;
        }

        .content h2 {
            color: #333;
        }

        .content-box {
            background: white;
            padding: 30px;
            margin-top: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        @media (max-width: 768px) {
            .content {
                margin-right: 0;
                padding: 15px;
            }
        }
    </style>
</head>
<body>

    @include('supplier.sidebar')

    <div class="content">
        <h2>مرحبًا بك في لوحة المورد</h2>
        <div class="content-box">
            <p>من هنا يمكنك إدارة بياناتك ومتابعة نشاطك.</p>
        </div>
    </div>

</body>
</html>
