<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>خطأ داخلي - توريد ميد</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Cairo', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .error-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            padding: 60px 40px;
            text-align: center;
            max-width: 500px;
            width: 100%;
        }
        
        .error-icon {
            font-size: 6rem;
            color: #ff6b6b;
            margin-bottom: 20px;
        }
        
        .error-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 15px;
        }
        
        .error-subtitle {
            color: #7f8c8d;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        
        .btn-home {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-block;
            margin: 10px;
        }
        
        .btn-home:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }
        
        .contact-support {
            margin-top: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 15px;
            border-left: 5px solid #667eea;
        }
        
        @media (max-width: 768px) {
            .error-container {
                padding: 40px 20px;
                margin: 20px;
            }
            
            .error-icon {
                font-size: 4rem;
            }
            
            .btn-home {
                display: block;
                width: 100%;
                margin: 10px 0;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">
            <i class="fas fa-bug"></i>
        </div>
        
        <h1 class="error-title">عذرًا! حدث خطأ</h1>
        <p class="error-subtitle">
            يبدو أن حدث خطأ داخلي في النظام. نحن نعمل على إصلاحه في أقرب وقت ممكن.
        </p>
        
        <a href="{{ route('frontend.home') }}" class="btn-home">
            <i class="fas fa-home me-2"></i>العودة للصفحة الرئيسية
        </a>
        
        <div class="contact-support">
            <h6><i class="fas fa-headset me-2 text-primary"></i>تحتاج مساعدة؟</h6>
            <p class="mb-0 text-muted small">
                تواصل مع فريق الدعم الفني على <strong>info@toreed.com</strong> أو 
                <strong>+20 123 456 7890</strong>
            </p>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
