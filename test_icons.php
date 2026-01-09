<!DOCTYPE html>
<html>
<head>
    <title>Icon Test</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .icon-test { 
            display: inline-block; 
            width: 30px; 
            height: 30px; 
            margin: 2px; 
            text-align: center; 
            line-height: 30px;
            border: 1px solid #ddd;
        }
        .invalid { 
            background: #ffcccc; 
            color: red;
        }
        .valid { 
            background: #ccffcc; 
            color: green;
        }
    </style>
</head>
<body>
<h2>Testing Medical Icons</h2>
<?php
$medical_icons = include 'config/medical-icons.php';
$all_icons = [];

foreach ($medical_icons as $category => $icons) {
    foreach ($icons as $icon) {
        $all_icons[] = $icon['icon'];
    }
}

$all_icons = array_unique($all_icons);
sort($all_icons);

echo "<h3>Found " . count($all_icons) . " unique icons</h3>";
echo "<div class='icons-grid'>";
foreach ($all_icons as $icon_name) {
    echo "<div class='icon-test' title='$icon_name'><i class='fas fa-$icon_name'></i></div>";
}
echo "</div>";

// Test certificate icons
echo "<h2>Testing Certificate Icons</h2>";
$certificate_icons = include 'config/certificate-icons.php';
$cert_icons = [];

foreach ($certificate_icons as $category => $icons) {
    foreach ($icons as $icon) {
        $cert_icons[] = $icon['icon'];
    }
}

$cert_icons = array_unique($cert_icons);
sort($cert_icons);

echo "<h3>Found " . count($cert_icons) . " unique icons</h3>";
echo "<div class='icons-grid'>";
foreach ($cert_icons as $icon_name) {
    echo "<div class='icon-test' title='$icon_name'><i class='fas fa-$icon_name'></i></div>";
}
echo "</div>";
?>

<script>
// Test icons by checking if they render
document.addEventListener('DOMContentLoaded', function() {
    const icons = document.querySelectorAll('.icon-test i');
    let invalidIcons = [];
    
    icons.forEach(icon => {
        const computedStyle = window.getComputedStyle(icon);
        const beforeContent = computedStyle.getPropertyValue('::before');
        
        // Check if icon is visible (has content and proper width)
        const rect = icon.getBoundingClientRect();
        if (rect.width === 0 || rect.height === 0) {
            icon.parentElement.classList.add('invalid');
            invalidIcons.push(icon.className.replace('fas fa-', ''));
        } else {
            icon.parentElement.classList.add('valid');
        }
    });
    
    console.log('Invalid icons:', invalidIcons);
    console.log('Total invalid:', invalidIcons.length);
});
</script>

</body>
</html>
