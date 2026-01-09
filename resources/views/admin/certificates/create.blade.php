<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة شهادة/جائزة</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: "Cairo", sans-serif;
            background: #f4f5fb;
            margin: 0;
        }
        .content {
            margin-right: 240px;
            padding: 30px;
        }
        .form-box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            max-width: 800px;
            margin: 0 auto;
        }
        h2 {
            color: #d97706;
            margin-bottom: 25px;
            text-align: center;
        }
        .form-group {
            margin-bottom: 25px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #374151;
        }
        input[type="text"], 
        input[type="number"],
        textarea,
        select {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-family: "Cairo", sans-serif;
            font-size: 16px;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }
        input[type="text"]:focus, 
        input[type="number"]:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: #d97706;
            box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.1);
        }
        textarea {
            resize: vertical;
            min-height: 100px;
        }
        select {
            cursor: pointer;
            background-color: white;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: left 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-left: 2.5rem;
            height: 48px;
        }
        .btn {
            background: #d97706;
            color: white;
            padding: 14px 30px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: 0.3s;
            margin-top: 10px;
        }
        .btn:hover {
            background: #b45309;
            transform: translateY(-1px);
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #d97706;
            text-decoration: none;
            font-weight: 600;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        .error {
            color: #ef4444;
            font-size: 14px;
            margin-top: 5px;
        }
        .success {
            background: #fef3c7;
            color: #78350f;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #fde68a;
            text-align: center;
        }
        .icon-preview-box {
            background: #fef3c7;
            padding: 20px;
            border-radius: 8px;
            margin-top: 10px;
            border: 2px solid #fde68a;
            text-align: center;
            min-height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 10px;
        }
        .icon-preview-box i {
            font-size: 48px;
            color: #d97706;
        }
        .icon-preview-box .icon-name {
            font-size: 14px;
            color: #78350f;
            font-weight: 500;
        }
        .preview-section {
            background: #fef3c7;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
            border-right: 4px solid #d97706;
        }
        .preview-section h5 {
            margin: 0 0 15px 0;
            color: #d97706;
            font-size: 16px;
        }
        .preview-content {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            padding: 15px;
            background: white;
            border-radius: 6px;
            min-height: 80px;
        }
        .preview-icon {
            font-size: 32px;
            color: #d97706;
            margin-top: 4px;
            flex-shrink: 0;
            min-width: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .preview-text {
            flex: 1;
        }
        .preview-title {
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
            font-size: 18px;
        }
        .preview-desc {
            color: #6b7280;
            line-height: 1.6;
        }
        .icon-label {
            font-size: 12px;
            color: #78350f;
            margin-top: 4px;
        }
        /* Custom Icon Selector Styles */
        .custom-icon-selector {
            position: relative;
        }
        .icon-dropdown-trigger {
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 12px;
            background: white;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: border-color 0.3s;
        }
        .icon-dropdown-trigger:hover {
            border-color: #d97706;
        }
        .selected-icon-display {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .selected-icon-display i {
            font-size: 18px;
            color: #d97706;
        }
        .dropdown-arrow {
            transition: transform 0.3s;
        }
        .dropdown-arrow.open {
            transform: rotate(180deg);
        }
        .icon-dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            z-index: 1000;
            max-height: 400px;
            overflow-y: auto;
            margin-top: 5px;
        }
        .icon-search {
            padding: 15px;
            border-bottom: 1px solid #e5e7eb;
            position: sticky;
            top: 0;
            background: white;
        }
        .icon-search input {
            width: 100%;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
        }
        .icon-category {
            margin-bottom: 15px;
        }
        .category-header {
            background: #fef3c7;
            padding: 10px 15px;
            font-weight: 600;
            color: #78350f;
            border-bottom: 1px solid #e5e7eb;
            position: sticky;
            top: 60px;
            z-index: 10;
        }
        .icon-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 8px;
            padding: 10px;
        }
        .icon-option {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.2s;
            border: 1px solid transparent;
        }
        .icon-option:hover {
            background: #fef3c7;
            border-color: #d97706;
        }
        .icon-option.selected {
            background: #fde68a;
            border-color: #d97706;
        }
        .icon-option i {
            font-size: 16px;
            color: #d97706;
            width: 20px;
            text-align: center;
        }
        .icon-option span {
            font-size: 12px;
            color: #374151;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .icon-option.hidden {
            display: none;
        }
        @media (max-width: 768px) {
            .content {
                margin-right: 0;
                padding: 15px;
            }
            .icon-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

@include('admin.sidebar')

<div class="content">
    <div class="form-box">
        <h2>إضافة شهادة/جائزة جديدة</h2>

        @if(session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div style="background: #fee2e2; color: #dc2626; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #fecaca;">
                <ul style="margin: 0; padding-right: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.certificates.store') }}">
            @csrf

            <div class="form-group">
                <label for="order">ترتيب العرض:</label>
                <input type="number" 
                       id="order" 
                       name="order" 
                       value="{{ old('order', 0) }}" 
                       min="0" 
                       max="999"
                       placeholder="0">
                <div style="color: #6b7280; font-size: 14px; margin-top: 5px;">اتركه فارغاً ليتم التعيين تلقائياً</div>
                @error('order')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="title">العنوان:</label>
                <input type="text" 
                       id="title" 
                       name="title" 
                       value="{{ old('title') }}" 
                       required 
                       placeholder="مثال: شهادة ISO 9001"
                       maxlength="255"
                       oninput="updatePreview()">
                @error('title')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="description">الوصف:</label>
                <textarea id="description" 
                          name="description" 
                          required 
                          placeholder="وصف مفصل للشهادة أو الجائزة..."
                          oninput="updatePreview()">{{ old('description') }}</textarea>
                @error('description')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="icon">اختر الأيقونة:</label>
                <div class="custom-icon-selector">
                    <input type="hidden" id="icon" name="icon" required>
                    <div class="icon-dropdown-trigger" onclick="toggleIconDropdown()">
                        <div class="selected-icon-display">
                            <i class="fas fa-question" id="selectedIcon"></i>
                            <span id="selectedIconLabel">-- اختر أيقونة --</span>
                        </div>
                        <div class="dropdown-arrow">▼</div>
                    </div>
                    <div class="icon-dropdown-menu" id="iconDropdownMenu" style="display: none;">
                        <div class="icon-search">
                            <input type="text" placeholder="بحث عن أيقونة..." onkeyup="filterIcons(this.value)">
                        </div>
                        
                        @php $categories = ['awards', 'certificates', 'quality', 'excellence', 'recognition', 'global']; @endphp
                        
                        @foreach($categories as $category)
                            @php $categoryData = config("certificate-icons.{$category}", []); @endphp
                            @if(!empty($categoryData))
                                @php 
                                    $categoryLabels = [
                                        'awards' => '🏆 الجوائز',
                                        'certificates' => '📜 الشهادات',
                                        'quality' => '✅ الجودة والتوثيق',
                                        'excellence' => '🎓 التميز والإنجاز',
                                        'recognition' => '🤝 التقدير والاعتراف',
                                        'global' => '🌍 عالمي ودولي'
                                    ];
                                @endphp
                                <div class="icon-category">
                                    <div class="category-header">{{ $categoryLabels[$category] ?? ucfirst($category) }}</div>
                                    <div class="icon-grid">
                                        @foreach($categoryData as $item)
                                            <div class="icon-option" data-icon="{{ $item['icon'] }}" data-label="{{ $item['label'] }}" onclick="selectIcon('{{ $item['icon'] }}', '{{ $item['label'] }}')">
                                                <i class="fas fa-{{ $item['icon'] }}"></i>
                                                <span>{{ $item['label'] }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                @error('icon')
                    <div class="error">{{ $message }}</div>
                @enderror

                <!-- معاينة الأيقونة المختارة -->
                <div class="icon-preview-box" id="iconPreviewBox" style="display: none;">
                    <i class="fas fa-question" id="iconPreviewIcon"></i>
                    <div class="icon-name" id="iconPreviewName">اختر أيقونة لعرضها</div>
                </div>
            </div>

            <!-- معاينة العنصر الكامل -->
            <div class="preview-section" id="previewSection" style="display: none;">
                <h5>معاينة العنصر</h5>
                <div class="preview-content">
                    <div class="preview-icon">
                        <i class="fas fa-question" id="fullPreviewIcon"></i>
                        <div class="icon-label" id="fullPreviewIconLabel"></div>
                    </div>
                    <div class="preview-text">
                        <div class="preview-title" id="fullPreviewTitle">العنوان</div>
                        <div class="preview-desc" id="fullPreviewDesc">الوصف سيظهر هنا...</div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn">إضافة الشهادة/الجائزة</button>
        </form>

        <a href="{{ route('admin.certificates.index') }}" class="back-link">← العودة للقائمة</a>
    </div>
</div>

<script>
// تمرير أسماء الأيقونات العربية للـ JavaScript
const iconsLabels = @json(collect(config('certificate-icons', []))->flatten(1)->mapWithKeys(function($item) {
    return [$item['icon'] => $item['label']];
})->toArray());

// Custom Icon Selector Functions
function toggleIconDropdown() {
    const dropdown = document.getElementById('iconDropdownMenu');
    const arrow = document.querySelector('.dropdown-arrow');
    const isVisible = dropdown.style.display !== 'none';
    
    dropdown.style.display = isVisible ? 'none' : 'block';
    arrow.classList.toggle('open', !isVisible);
    
    if (!isVisible) {
        document.querySelector('.icon-search input').focus();
    }
}

function selectIcon(iconName, iconLabel) {
    const hiddenInput = document.getElementById('icon');
    const selectedIcon = document.getElementById('selectedIcon');
    const selectedLabel = document.getElementById('selectedIconLabel');
    const dropdown = document.getElementById('iconDropdownMenu');
    const arrow = document.querySelector('.dropdown-arrow');
    
    // Update hidden input value
    hiddenInput.value = iconName;
    
    // Update display
    selectedIcon.className = `fas fa-${iconName}`;
    selectedLabel.textContent = iconLabel;
    
    // Close dropdown
    dropdown.style.display = 'none';
    arrow.classList.remove('open');
    
    // Remove selected class from all options
    document.querySelectorAll('.icon-option').forEach(opt => {
        opt.classList.remove('selected');
    });
    
    // Add selected class to chosen option
    const chosenOption = document.querySelector(`[data-icon="${iconName}"]`);
    if (chosenOption) {
        chosenOption.classList.add('selected');
    }
    
    // Update preview
    updateIconPreview();
}

function filterIcons(searchTerm) {
    const term = searchTerm.toLowerCase();
    const options = document.querySelectorAll('.icon-option');
    
    options.forEach(option => {
        const label = option.querySelector('span').textContent.toLowerCase();
        const icon = option.getAttribute('data-icon').toLowerCase();
        
        if (label.includes(term) || icon.includes(term)) {
            option.classList.remove('hidden');
        } else {
            option.classList.add('hidden');
        }
    });
    
    // Hide empty categories
    document.querySelectorAll('.icon-category').forEach(category => {
        const visibleOptions = category.querySelectorAll('.icon-option:not(.hidden)');
        const header = category.querySelector('.category-header');
        
        if (visibleOptions.length === 0) {
            header.style.display = 'none';
        } else {
            header.style.display = 'block';
        }
    });
}

// Close dropdown when clicking outside
document.addEventListener('click', function(e) {
    const selector = document.querySelector('.custom-icon-selector');
    if (!selector.contains(e.target)) {
        document.getElementById('iconDropdownMenu').style.display = 'none';
        document.querySelector('.dropdown-arrow').classList.remove('open');
    }
});

// تحديث معاينة الأيقونة المختارة
function updateIconPreview() {
    const iconValue = document.getElementById('icon').value;
    const iconPreviewBox = document.getElementById('iconPreviewBox');
    const iconPreviewIcon = document.getElementById('iconPreviewIcon');
    const iconPreviewName = document.getElementById('iconPreviewName');
    
    if (iconValue) {
        iconPreviewBox.style.display = 'flex';
        iconPreviewIcon.className = `fas fa-${iconValue}`;
        iconPreviewName.textContent = iconsLabels[iconValue] || iconValue;
    } else {
        iconPreviewBox.style.display = 'none';
        iconPreviewIcon.className = 'fas fa-question';
        iconPreviewName.textContent = 'اختر أيقونة لعرضها';
    }
    
    updatePreview();
}

// تحديث معاينة العنصر الكامل
function updatePreview() {
    const title = document.getElementById('title').value || 'العنوان';
    const description = document.getElementById('description').value || 'الوصف سيظهر هنا...';
    const icon = document.getElementById('icon').value || 'question';
    
    const previewSection = document.getElementById('previewSection');
    const fullPreviewTitle = document.getElementById('fullPreviewTitle');
    const fullPreviewDesc = document.getElementById('fullPreviewDesc');
    const fullPreviewIcon = document.getElementById('fullPreviewIcon');
    const fullPreviewIconLabel = document.getElementById('fullPreviewIconLabel');
    
    if (title || description || icon !== 'question') {
        previewSection.style.display = 'block';
        fullPreviewTitle.textContent = title;
        fullPreviewDesc.textContent = description;
        fullPreviewIcon.className = `fas fa-${icon}`;
        fullPreviewIconLabel.textContent = iconsLabels[icon] ? `(${iconsLabels[icon]})` : '';
    } else {
        previewSection.style.display = 'none';
    }
}

// تحديث المعاينة عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    // Set initial selected icon if there's an old value
    const oldIcon = "{{ old('icon') }}";
    if (oldIcon && iconsLabels[oldIcon]) {
        selectIcon(oldIcon, iconsLabels[oldIcon]);
    }
    
    updateIconPreview();
    updatePreview();
});
</script>

</body>
</html>
