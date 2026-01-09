<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>رسالة جديدة من توريد ميد</title>
    <style>
        body {
            font-family: 'Segoe UI', 'Tahoma', 'Arial', sans-serif;
            line-height: 1.8;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            font-size: 18px; /* ضعف الحجم من 9px إلى 18px */
        }
        .email-container {
            max-width: 700px; /* زيادة العرض قليلاً */
            margin: 0 auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            background-color: #2c3e50;
            color: white;
            padding: 40px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 40px; /* ضعف الحجم من 28px */
        }
        .email-body {
            padding: 40px;
            font-size: 20px; /* ضعف الحجم */
        }
        .message-details {
            background: #f8f9fa;
            border-radius: 6px;
            padding: 30px;
            margin-bottom: 30px;
            border-right: 4px solid #3498db;
        }
        .detail-row {
            margin-bottom: 18px;
            padding-bottom: 18px;
            border-bottom: 1px solid #eee;
            font-size: 20px; /* ضعف الحجم */
        }
        .detail-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        .detail-label {
            font-weight: bold;
            color: #2c3e50;
            display: inline-block;
            min-width: 120px;
            font-size: 22px; /* ضعف الحجم */
        }
        .message-content {
            white-space: pre-line;
            line-height: 2;
            background: #fff;
            border: 1px solid #ddd;
            padding: 30px;
            border-radius: 6px;
            margin-top: 25px;
            font-size: 20px; /* ضعف الحجم */
        }
        .email-footer {
            text-align: center;
            padding: 30px;
            background: #f8f9fa;
            color: #6c757d;
            font-size: 18px; /* ضعف الحجم من 14px */
        }
        .btn {
            display: inline-block;
            padding: 18px 35px; /* ضعف الحجم */
            background-color: #3498db;
            color: white !important;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            font-weight: bold;
            font-size: 20px; /* ضعف الحجم */
        }
        .btn:hover {
            background-color: #2980b9;
        }
        .type-badge {
            display: inline-block;
            padding: 8px 18px; /* ضعف الحجم */
            border-radius: 20px;
            font-size: 18px; /* ضعف الحجم من 12px */
            font-weight: bold;
            text-transform: uppercase;
        }
        .type-customer {
            background-color: #e8f5e9;
            color: #2e7d32;
        }
        .type-supplier {
            background-color: #fff3e0;
            color: #f57c00;
        }
        h3 {
            font-size: 28px; /* ضعف الحجم */
            margin-top: 25px;
            margin-bottom: 15px;
        }
        p {
            font-size: 20px; /* ضعف الحجم */
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>📧 رسالة جديدة من موقع توريد ميد</h1>
        </div>
        
        <div class="email-body">
            <p>مرحباً،</p>
            <p>لقد تلقيت رسالة جديدة من نموذج الاتصال في موقع توريد ميد. إليك تفاصيل الرسالة:</p>
            
            <div class="message-details">
                <div class="detail-row">
                    <span class="detail-label">الاسم:</span>
                    <span>{{ $name }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">البريد:</span>
                    <a href="mailto:{{ $email }}" style="color: #3498db; text-decoration: none;">{{ $email }}</a>
                </div>
                <div class="detail-row">
                    <span class="detail-label">الهاتف:</span>
                    <a href="tel:{{ $phone }}" style="color: #3498db; text-decoration: none;">{{ $phone }}</a>
                </div>
                <div class="detail-row">
                    <span class="detail-label">النوع:</span>
                    <span class="type-badge type-{{ $accountType }}">{{ $accountType == 'customer' ? 'عميل' : 'مورد' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">الموضوع:</span>
                    <strong>{{ $subject }}</strong>
                </div>
                <div class="detail-row">
                    <span class="detail-label">التاريخ:</span>
                    <span>{{ now()->format('Y-m-d H:i:s') }}</span>
                </div>
            </div>
            
            <h3>📝 نص الرسالة:</h3>
            <div class="message-content">
                {{ $messageContent }}
            </div>
            
            <div style="margin-top: 40px; text-align: center;">
                <a href="mailto:{{ $email }}?subject={{ urlencode('رد على: ' . $subject) }}" class="btn">📨 الرد على المرسل</a>
            </div>
        </div>
        
        <div class="email-footer">
            <p>هذه رسالة آلية من نظام توريد ميد</p>
            <p>© {{ date('Y') }} توريد ميد. جميع الحقوق محفوظة.</p>
        </div>
    </div>
</body>
</html>
