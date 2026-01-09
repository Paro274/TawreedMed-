<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Valid Icons</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #f5f5f5;
        }
        .category {
            background: white;
            margin: 20px 0;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .category h2 {
            color: #333;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }
        .icon-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 10px;
            margin-top: 15px;
        }
        .icon-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: #f9f9f9;
        }
        .icon-item i {
            font-size: 18px;
            color: #007bff;
            width: 20px;
            text-align: center;
        }
        .icon-item span {
            font-size: 12px;
            color: #666;
        }
        .invalid {
            background: #ffebee;
            border-color: #f44336;
        }
        .invalid i {
            color: #f44336;
        }
        .stats {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h1>اختبار أيقونات FontAwesome الصالحة</h1>
    
    <div class="stats" id="stats">
        <h3>الإحصائيات:</h3>
        <div id="stats-content">جاري التحميل...</div>
    </div>

    <?php
    // Test medical icons
    $medical_icons = include 'config/medical-icons.php';
    echo '<div class="category">';
    echo '<h2>أيقونات طبية (Medical Icons)</h2>';
    
    $total_medical = 0;
    $valid_medical = 0;
    
    foreach ($medical_icons as $category => $icons) {
        echo "<h3>" . htmlspecialchars($category) . "</h3>";
        echo '<div class="icon-grid">';
        
        foreach ($icons as $icon) {
            $total_medical++;
            echo '<div class="icon-item" data-icon="' . htmlspecialchars($icon['icon']) . '">';
            echo '<i class="fas fa-' . htmlspecialchars($icon['icon']) . '"></i>';
            echo '<span>' . htmlspecialchars($icon['label']) . '</span>';
            echo '</div>';
        }
        
        echo '</div>';
    }
    echo '</div>';
    
    // Test certificate icons
    $certificate_icons = include 'config/certificate-icons.php';
    echo '<div class="category">';
    echo '<h2>أيقونات الشهادات (Certificate Icons)</h2>';
    
    $total_certificate = 0;
    
    foreach ($certificate_icons as $category => $icons) {
        echo "<h3>" . htmlspecialchars($category) . "</h3>";
        echo '<div class="icon-grid">';
        
        foreach ($icons as $icon) {
            $total_certificate++;
            echo '<div class="icon-item" data-icon="' . htmlspecialchars($icon['icon']) . '">';
            echo '<i class="fas fa-' . htmlspecialchars($icon['icon']) . '"></i>';
            echo '<span>' . htmlspecialchars($icon['label']) . '</span>';
            echo '</div>';
        }
        
        echo '</div>';
    }
    echo '</div>';
    ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const iconItems = document.querySelectorAll('.icon-item');
            let invalidCount = 0;
            let validCount = 0;
            
            iconItems.forEach(item => {
                const icon = item.querySelector('i');
                const computedStyle = window.getComputedStyle(icon, '::before');
                const content = computedStyle.getPropertyValue('content');
                
                // Check if icon is actually rendered
                const rect = icon.getBoundingClientRect();
                const isVisible = rect.width > 0 && rect.height > 0;
                
                if (!isVisible || content === 'none' || content === '') {
                    item.classList.add('invalid');
                    invalidCount++;
                } else {
                    validCount++;
                }
            });
            
            // Update statistics
            const statsContent = document.getElementById('stats-content');
            statsContent.innerHTML = `
                <p><strong>إجمالي الأيقونات:</strong> ${iconItems.length}</p>
                <p><strong>الأيقونات الصالحة:</strong> <span style="color: green;">${validCount}</span></p>
                <p><strong>الأيقونات غير الصالحة:</strong> <span style="color: red;">${invalidCount}</span></p>
                <p><strong>نسبة النجاح:</strong> ${((validCount / iconItems.length) * 100).toFixed(1)}%</p>
            `;
            
            console.log(`Valid icons: ${validCount}, Invalid icons: ${invalidCount}`);
        });
    </script>
</body>
</html>
