<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل الميزة - {{ $feature->title }}</title>
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
            max-width: 900px;
            margin: 0 auto;
        }
        h2 {
            color: #333;
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
        textarea:focus,
        select:focus {
            outline: none;
            border-color: #059669;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
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
            background: #059669;
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
            background: #047857;
            transform: translateY(-1px);
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #059669;
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
            background: #dcfce7;
            color: #166534;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #bbf7d0;
            text-align: center;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .current-feature {
            background: #f0f9ff;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            border-left: 4px solid #3b82f6;
        }
        .current-feature h4 {
            margin: 0 0 15px 0;
            color: #1e40af;
            font-size: 18px;
        }
        .current-feature-content {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            padding: 15px;
            background: white;
            border-radius: 6px;
        }
        .current-icon {
            font-size: 24px;
            color: #3b82f6;
            margin-top: 4px;
            flex-shrink: 0;
        }
        .current-text {
            flex: 1;
        }
        .current-title {
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
            font-size: 18px;
        }
        .current-desc {
            color: #6b7280;
            line-height: 1.6;
        }
        .icon-preview-box {
            background: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            margin-top: 10px;
            border: 2px solid #e5e7eb;
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
            color: #059669;
        }
        .icon-preview-box .icon-name {
            font-size: 14px;
            color: #6b7280;
            font-weight: 500;
        }
        .preview-section {
            background: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
            border-left: 4px solid #059669;
        }
        .preview-section h5 {
            margin: 0 0 15px 0;
            color: #059669;
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
            color: #059669;
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
            color: #9ca3af;
            margin-top: 4px;
        }
        @media (max-width: 768px) {
            .content {
                margin-right: 0;
                padding: 15px;
            }
            .form-row {
                grid-template-columns: 1fr;
            }
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
        .category-icons {
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
            .category-icons {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

@include('admin.sidebar')

<div class="content">
    <div class="form-box">
        <h2>تعديل الميزة: {{ $feature->title }}</h2>

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

        <!-- عرض الميزة الحالية -->
        <div class="current-feature">
            <h4>الميزة الحالية:</h4>
            <div class="current-feature-content">
                <div class="current-icon">
                    <i class="fas fa-{{ $feature->icon }}"></i>
                    <div class="icon-label">
                        @php
                            $allIcons = collect(config('medical-icons', []))->flatten(1)->mapWithKeys(function($item) {
                                return [$item['icon'] => $item['label']];
                            })->toArray();
                        @endphp
                        {{ $allIcons[$feature->icon] ?? $feature->icon }}
                    </div>
                </div>
                <div class="current-text">
                    <div class="current-title">{{ $feature->title }}</div>
                    <div class="current-desc">{{ $feature->description }}</div>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.features.update', $feature) }}">
            @csrf
            @method('PUT')

            <div class="form-row">
                <div class="form-group">
                    <label for="order">ترتيب العرض:</label>
                    <input type="number" 
                           id="order" 
                           name="order" 
                           value="{{ old('order', $feature->order) }}" 
                           min="0" 
                           max="999"
                           placeholder="0">
                    <div style="color: #6b7280; font-size: 14px; margin-top: 5px;">اتركه فارغاً ليتم التعيين تلقائياً</div>
                    @error('order')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="title">العنوان:</label>
                <input type="text" 
                       id="title" 
                       name="title" 
                       value="{{ old('title', $feature->title) }}" 
                       required 
                       placeholder="مثال: توصيل سريع للأدوية"
                       maxlength="100"
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
                          placeholder="وصف مفصل للميزة..."
                          oninput="updatePreview()">{{ old('description', $feature->description) }}</textarea>
                @error('description')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="icon">اختر الأيقونة:</label>
                <div class="custom-icon-selector">
                    <input type="hidden" id="icon" name="icon" required value="{{ old('icon', $feature->icon) }}">
                    <div class="icon-dropdown-trigger" onclick="toggleIconDropdown()">
                        <div class="selected-icon-display">
                            <i class="fas fa-{{ old('icon', $feature->icon) ?: 'question' }}" id="selectedIcon"></i>
                            <span id="selectedIconLabel">{{ $allIcons[old('icon', $feature->icon)] ?? old('icon', $feature->icon) ?? '-- اختر أيقونة --' }}</span>
                        </div>
                        <div class="dropdown-arrow">▼</div>
                    </div>
                    <div class="icon-dropdown-menu" id="iconDropdownMenu" style="display: none;">
                        <div class="icon-search">
                            <input type="text" placeholder="بحث عن أيقونة..." onkeyup="filterIcons(this.value)">
                        </div>
                        <div class="icon-categories" id="iconCategories">
                            @php $categories = ['medications', 'cosmetics', 'medical-supplies', 'services', 'health', 'devices', 'quality', 'support', 'commerce', 'innovation']; @endphp
                            
                            @foreach($categories as $category)
                                @php $categoryData = config("medical-icons.{$category}", []); @endphp
                                @if(!empty($categoryData))
                                    @php 
                                        $categoryLabels = [
                                            'medications' => '🔴 الأدوية والصيدليات',
                                            'cosmetics' => '💄 مستحضرات التجميل',
                                            'medical-supplies' => '🏥 المستلزمات الطبية',
                                            'services' => '👨‍⚕️ الخدمات الطبية',
                                            'health' => '💚 الصحة والعافية',
                                            'devices' => '🔬 الأجهزة الطبية',
                                            'quality' => '✅ الجودة والشهادات',
                                            'support' => '📞 الدعم والخدمة',
                                            'commerce' => '🛒 التجارة والتسوق',
                                            'innovation' => '🔬 الابتكار والبحث'
                                        ];
                                    @endphp
                                    <div class="icon-category">
                                        <div class="category-header">{{ $categoryLabels[$category] ?? ucfirst($category) }}</div>
                                        <div class="category-icons">
                                            @foreach($categoryData as $item)
                                                <div class="icon-option" 
                                                     data-icon="{{ $item['icon'] }}" 
                                                     data-label="{{ $item['label'] }}"
                                                     onclick="selectIcon('{{ $item['icon'] }}', '{{ $item['label'] }}')">
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
                </div>
                @error('icon')
                    <div class="error">{{ $message }}</div>
                @enderror

                <!-- معاينة الأيقونة المختارة -->
                <div class="icon-preview-box" id="iconPreviewBox">
                    <i class="fas fa-{{ old('icon', $feature->icon) }}" id="iconPreviewIcon"></i>
                    <div class="icon-name" id="iconPreviewName">
                        @php echo $allIcons[old('icon', $feature->icon)] ?? old('icon', $feature->icon); @endphp
                    </div>
                </div>
            </div>

            <!-- معاينة الميزة الكاملة -->
            <div class="preview-section" id="previewSection">
                <h5>معاينة الميزة المحدثة</h5>
                <div class="preview-content">
                    <div class="preview-icon">
                        <i class="fas fa-{{ old('icon', $feature->icon) }}" id="fullPreviewIcon"></i>
                        <div class="icon-label" id="fullPreviewIconLabel">
                            ({{ $allIcons[old('icon', $feature->icon)] ?? old('icon', $feature->icon) }})
                        </div>
                    </div>
                    <div class="preview-text">
                        <div class="preview-title" id="fullPreviewTitle">{{ old('title', $feature->title) }}</div>
                        <div class="preview-desc" id="fullPreviewDesc">{{ old('description', $feature->description) }}</div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn">تحديث الميزة</button>
        </form>

        <a href="{{ route('admin.features.index') }}" class="back-link">← العودة إلى قائمة المميزات</a>
    </div>
</div>

<script>
// تمرير أسماء الأيقونات العربية للـ JavaScript
const iconsLabels = @json(collect(config('medical-icons', []))->flatten(1)->mapWithKeys(function($item) {
    return [$item['icon'] => $item['label']];
})->toArray());

// تحديث معاينة الأيقونة المختارة
function updateIconPreview() {
    const iconSelect = document.getElementById('icon');
    const selectedIcon = iconSelect.value;
    const iconPreviewBox = document.getElementById('iconPreviewBox');
    const iconPreviewIcon = document.getElementById('iconPreviewIcon');
    const iconPreviewName = document.getElementById('iconPreviewName');
    
    if (selectedIcon) {
        iconPreviewBox.style.display = 'flex';
        iconPreviewIcon.className = `fas fa-${selectedIcon}`;
        iconPreviewName.textContent = iconsLabels[selectedIcon] || selectedIcon;
    } else {
        iconPreviewBox.style.display = 'none';
        iconPreviewIcon.className = 'fas fa-question';
        iconPreviewName.textContent = 'اختر أيقونة لعرضها';
    }
    
    // تحديث المعاينة الكاملة
    updatePreview();
}

// تحديث معاينة الميزة الكاملة
function updatePreview() {
    const title = document.getElementById('title').value || '{{ $feature->title }}';
    const description = document.getElementById('description').value || '{{ $feature->description }}';
    const icon = document.getElementById('icon').value || '{{ $feature->icon }}';
    
    const previewSection = document.getElementById('previewSection');
    const fullPreviewTitle = document.getElementById('fullPreviewTitle');
    const fullPreviewDesc = document.getElementById('fullPreviewDesc');
    const fullPreviewIcon = document.getElementById('fullPreviewIcon');
    const fullPreviewIconLabel = document.getElementById('fullPreviewIconLabel');
    
    if (title || description || icon) {
        previewSection.style.display = 'block';
        fullPreviewTitle.textContent = title;
        fullPreviewDesc.textContent = description;
        fullPreviewIcon.className = `fas fa-${icon}`;
        fullPreviewIconLabel.textContent = iconsLabels[icon] ? `(${iconsLabels[icon]})` : '';
    } else {
        previewSection.style.display = 'none';
    }
}

// تحديث المعاينة عند تحميل الصفحة (للقيم القديمة)
document.addEventListener('DOMContentLoaded', function() {
    updateIconPreview();
    updatePreview();
    
    // Initialize selected icon display
    const currentIcon = document.getElementById('icon').value;
    if (currentIcon) {
        const selectedIcon = document.getElementById('selectedIcon');
        const selectedLabel = document.getElementById('selectedIconLabel');
        selectedIcon.className = `fas fa-${currentIcon}`;
        selectedLabel.textContent = iconsLabels[currentIcon] || currentIcon;
    }
});

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
</script>

</body>
</html>
