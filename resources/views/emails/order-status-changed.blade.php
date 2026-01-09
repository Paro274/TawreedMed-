<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f3f4f6; padding: 20px; margin: 0; }
        .container { background-color: #ffffff; max-width: 600px; margin: 0 auto; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); overflow: hidden; }
        .header { background-color: #4f46e5; color: #ffffff; padding: 20px; text-align: center; }
        .content { padding: 30px; text-align: center; color: #374151; }
        .status-badge { 
            display: inline-block; background-color: #e0f2fe; color: #0369a1; 
            padding: 15px 30px; border-radius: 50px; font-size: 20px; font-weight: bold; margin: 20px 0; 
            border: 2px solid #0ea5e9;
        }
        .footer { background-color: #f9fafb; padding: 15px; text-align: center; font-size: 12px; color: #6b7280; }
        .btn { display: inline-block; background-color: #4f46e5; color: white; text-decoration: none; padding: 12px 25px; border-radius: 5px; margin-top: 20px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2 style="margin:0;">تحديث حالة الطلب</h2>
        </div>
        <div class="content">
            <p style="font-size: 16px;">مرحباً <strong>{{ $order->shipping_name }}</strong>،</p>
            <p>تم تحديث حالة طلبك رقم <strong>#{{ $order->order_number }}</strong> إلى:</p>
            
            <div class="status-badge">
                {{ $order->status_label }}
            </div>

            <p>شكراً لتسوقك معنا في توريد ميد.</p>
            
            {{-- تأكد إن الراوت ده موجود عندك للعميل --}}
            <a href="{{ url('/') }}" class="btn">زيارة الموقع</a>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Toreed Med. جميع الحقوق محفوظة.
        </div>
    </div>
</body>
</html>
