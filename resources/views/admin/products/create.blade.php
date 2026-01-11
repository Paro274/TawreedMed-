<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>إضافة منتج جديد - لوحة التحكم</title>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<style>
    body { font-family:"Cairo",sans-serif;background:#f2f4f8; }
    .container { margin-right:240px; padding:30px; }
    .form-box {
        background:white; padding:25px 30px; border-radius:10px;
        box-shadow:0 5px 15px rgba(0,0,0,0.1); max-width:900px; margin:auto;
    }
    h2 { color:#111827; margin-bottom:20px; }
    label { font-weight:700; display:block; margin-bottom:5px; color: #374151; }
    
    input, select, textarea {
        width:100%; padding:10px; margin-bottom:15px;
        border:1px solid #ccc; border-radius:6px; font-family:"Cairo",sans-serif;
        box-sizing: border-box; font-weight: bold; color: #333;
    }
    
    button {
        background:#4f46e5; color:white; font-weight:600; border:none;
        border-radius:6px; padding:10px 15px; cursor:pointer; transition:0.3s;
    }
    button:hover { background:#4338ca; }
    .file-upload input { padding:6px; background:#fafafa; border:1px dashed #ccc; }
    h3 { color: #374151; border-bottom: 2px solid #e5e7eb; padding-bottom: 5px; margin-top: 25px; margin-bottom: 15px; font-weight: 800; }
    
    #final_price, #package_price_display {
        font-size: 1.1rem; text-align: center; border: 2px solid #ddd;
        background: #f8f9fa; font-weight: 800; color: #000;
    }
    
    #discount-field {
        background: linear-gradient(135deg, #f0fdf4, #dcfce7); padding: 20px;
        border-radius: 12px; border: 2px solid #10b981; margin-bottom: 25px; display: none;
    }
    .ingredient-row { display: flex; gap: 10px; margin-bottom: 10px; }
    .ingredient-row input { flex: 1; margin-bottom: 0; }
    .btn-add { background: #10b981; margin-bottom: 15px; display: inline-block; }
    .btn-remove { background: #ef4444; padding: 0 15px; }
    .btn-remove:hover { background: #dc2626; }
    .row-2-cols { display: flex; gap: 20px; }
    .row-2-cols > div { flex: 1; }
    .medicine-only { display: none; }

    .label-with-btn {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 5px;
    }
    .btn-quick-add {
        background: #10b981;
        font-size: 12px;
        padding: 4px 10px;
        border-radius: 4px;
        color: white;
        text-decoration: none;
        cursor: pointer;
        border: none;
    }
    .btn-quick-add:hover { background: #059669; }

    .modal-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.5); z-index: 1000;
        display: none; justify-content: center; align-items: center;
    }
    .modal-box {
        background: white; padding: 25px; border-radius: 10px;
        width: 100%; max-width: 500px;
        position: relative;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }
    .modal-close {
        position: absolute; left: 15px; top: 15px;
        background: none; color: #666; font-size: 20px;
        padding: 0; cursor: pointer;
    }
    .modal-title { margin-top: 0; color: #4f46e5; border-bottom: 1px solid #eee; padding-bottom: 10px; }
    .error-msg { 
        color: #dc2626; 
        background-color: #fef2f2;
        border: 1px solid #fecaca;
        padding: 10px;
        border-radius: 6px;
        font-size: 13px; 
        margin-bottom: 15px; 
        display: none; 
    }
    .error-msg ul { margin: 0; padding-right: 20px; }
</style>
</head>
<body>

@include('admin.sidebar')

<div class="container">
<div class="form-box">
<h2>إضافة منتج جديد</h2>

@if(session('success'))<p style="color:green;">{{ session('success') }}</p>@endif

<form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
@csrf

<div class="label-with-btn">
    <label>اختر المورد:</label>
    <button type="button" class="btn-quick-add" onclick="openModal('supplierModal')">
        <i class="fas fa-plus"></i> إضافة مورد جديد
    </button>
</div>
<select name="supplier_id" id="supplier_select" required>
<option value="">اختر المورد</option>
@foreach($suppliers as $supplier)
<option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
@endforeach
</select>

<label>نوع المنتج:</label>
<select name="product_type" id="product_type" onchange="loadCategories();" required>
<option value="">اختر النوع</option>
<option value="أدوية">أدوية</option>
<option value="مستلزمات طبية">مستلزمات طبية</option>
<option value="منتجات تجميل">منتجات تجميل</option>
</select>

<div class="label-with-btn">
    <label>التصنيف:</label>
    <button type="button" class="btn-quick-add" onclick="openModal('categoryModal')">
        <i class="fas fa-plus"></i> إضافة تصنيف جديد
    </button>
</div>
<select name="category_id" id="category_id" required>
<option value="">اختر التصنيف</option>
</select>

<label>اسم المنتج:</label>
<input type="text" name="name" required>

<h3>معلومات السعر والتعبئة</h3>

<label for="price">السعر للقطعة (للجملة):</label>
<input type="number" id="price" step="0.01" name="price" required oninput="calculateTotals()">

<div id="discount-field">
    <label>نسبة الخصم (%):</label>
    <input type="number" id="discount" step="0.01" name="discount" oninput="calculateTotals()" placeholder="مثال: 20" min="0" max="100">
</div>

<div id="final-price-row">
    <label>السعر النهائي للوحدة:</label>
    <input type="text" id="final_price" readonly>
</div>

<label>نوع الباكيدج (العبوة الكبيرة):</label>
<select name="package_type" id="package_type">
<option value="كرتونة">كرتونة</option>
<option value="كيس">كيس</option>
<option value="علبة">علبة</option>
</select>

<label>عدد الوحدات داخل الباكيدج:</label>
<input type="number" id="units_per_package" name="units_per_package" required oninput="calculateTotals()">

<label>سعر الباكيدج بالكامل:</label>
<input type="text" id="package_price_display" readonly>

<div class="row-2-cols">
    <div>
        <label>أقل كمية للطلب (بالوحدة/القطعة):</label>
        <input type="number" name="min_order_quantity" placeholder="مثال: 10 قطع">
    </div>
    <div>
        <label>أقل كمية للطلب (بالباكيدج/الكرتونة):</label>
        <input type="number" name="min_order_package" placeholder="مثال: 1 كرتونة">
    </div>
</div>

<label for="stock_quantity">إجمالي الكمية (Total Quantity) (اختياري):</label>
<input type="number" id="stock_quantity" name="stock_quantity" placeholder="مثال: 100">

<h3>تفاصيل إضافية</h3>

<label>اسم الشركة (اختياري):</label>
<input type="text" name="company_name">

<label>الوصف المختصر:</label>
<textarea class="tiny-editor" name="short_description"></textarea>

<label>الوصف التفصيلي:</label>
<textarea class="tiny-editor" name="full_description"></textarea>

<div class="medicine-only">
    <h3>المواصفات الطبية</h3>
    <label>المواد الفعالة:</label>
    <div id="ingredients-container">
        <div class="ingredient-row">
            <input type="text" name="active_ingredient[]" placeholder="اسم المادة الفعالة">
        </div>
    </div>
    <button type="button" class="btn-add" onclick="addIngredient()">+ إضافة مادة أخرى</button>

    <label>الشكل الدوائي:</label>
    <input type="text" name="dosage_form">

    <label>عدد أقراص العبوة:</label>
    <input type="number" name="tablets_per_pack">
</div>

<label>تاريخ الإنتاج (اختياري):</label>
<input type="date" name="production_date">

<label>تاريخ الانتهاء (اختياري):</label>
<input type="date" name="expiry_date">

<h3>الصور (حتى 4)</h3>
<div class="file-upload">
@for($i=1;$i<=4;$i++)
<input type="file" name="image_{{ $i }}">
@endfor
</div>

<button type="submit">حفظ المنتج</button>
</form>
</div>
</div>

<div id="supplierModal" class="modal-overlay">
    <div class="modal-box">
        <button type="button" class="modal-close" onclick="closeModal('supplierModal')">&times;</button>
        <h3 class="modal-title">إضافة مورد جديد سريعاً</h3>
        <div id="supplierErrors" class="error-msg"></div>
        <form id="quickSupplierForm" onsubmit="submitQuickSupplier(event)" enctype="multipart/form-data">
            <label>اسم المورد:</label>
            <input type="text" name="name" required>
            <label>البريد الإلكتروني:</label>
            <input type="email" name="email" required>
            <label>رقم الهاتف:</label>
            <input type="text" name="phone">
            <label>كلمة المرور:</label>
            <input type="password" name="password" required placeholder="كلمة مرور مؤقتة">
            <label>شعار المورد (اختياري):</label>
            <input type="file" name="logo" accept="image/*" style="padding: 5px; background: #f9f9f9;">
            <button type="submit" style="width:100%; margin-top:10px;">حفظ وإضافة</button>
        </form>
    </div>
</div>

<div id="categoryModal" class="modal-overlay">
    <div class="modal-box">
        <button type="button" class="modal-close" onclick="closeModal('categoryModal')">&times;</button>
        <h3 class="modal-title">إضافة تصنيف جديد سريعاً</h3>
        <div id="categoryErrors" class="error-msg"></div>
        <form id="quickCategoryForm" onsubmit="submitQuickCategory(event)">
            <label>اسم التصنيف:</label>
            <input type="text" name="name" required>
            <label>نوع المنتج:</label>
            <select name="product_type" required>
                <option value="أدوية">أدوية</option>
                <option value="مستلزمات طبية">مستلزمات طبية</option>
                <option value="منتجات تجميل">منتجات تجميل</option>
            </select>
            <button type="submit" style="width:100%; margin-top:10px;">حفظ وإضافة</button>
        </form>
    </div>
</div>

<script>
function getArabicError(field, message) {
    const fieldNames = {
        'name': 'الاسم',
        'email': 'البريد الإلكتروني',
        'phone': 'رقم الهاتف',
        'password': 'كلمة المرور',
        'logo': 'الشعار',
        'product_type': 'نوع المنتج'
    };
    
    const fieldAr = fieldNames[field] || field;

    if (message.includes('required')) return `حقل ${fieldAr} مطلوب.`;
    if (message.includes('taken') || message.includes('unique')) return `قيمة ${fieldAr} مسجلة مسبقاً، يرجى استخدام قيمة أخرى.`;
    if (message.includes('email')) return `صيغة البريد الإلكتروني غير صحيحة.`;
    if (message.includes('min')) return `حقل ${fieldAr} قصير جداً.`;
    if (message.includes('max')) return `حقل ${fieldAr} طويل جداً.`;
    if (message.includes('image')) return `يجب أن يكون الملف صورة (jpg, png, etc).`;
    
    return `يوجد خطأ في حقل ${fieldAr}.`;
}

function toggleFieldsByProductType() {
    const productType = document.getElementById('product_type').value;
    
    const medicineOnlyFields = document.querySelectorAll('.medicine-only');
    const discountField = document.getElementById('discount-field');
    const priceLabel = document.querySelector('label[for="price"]');
    const finalPriceRow = document.getElementById('final-price-row');
    const packageTypeSelect = document.getElementById('package_type');
    
    // Common fields for all types
    medicineOnlyFields.forEach(field => field.style.display = (productType === 'أدوية') ? 'block' : 'none');
    
    if (productType === 'أدوية') {
        discountField.style.display = 'block';
        finalPriceRow.style.display = 'block';
        priceLabel.textContent = 'السعر للقطعة (قبل الخصم):';
    } else {
        discountField.style.display = 'none';
        finalPriceRow.style.display = 'none';
        document.getElementById('discount').value = ''; 
        priceLabel.textContent = 'السعر للقطعة (للجملة):';
    }
    
    if (productType === 'أدوية') {
        
        packageTypeSelect.innerHTML = `
            <option value="علبة">علبة</option>
            <option value="كيس">كيس</option>
            <option value="دستة">دستة</option>
            <option value="كرتونة">كرتونة</option>
        `;
    }
    
    calculateTotals();
}

function loadCategories() {
    const type = document.getElementById('product_type').value;
    const categorySelect = document.getElementById('category_id');

    if (type.trim() === '') {
        categorySelect.innerHTML = '<option value="">اختر التصنيف</option>';
        toggleFieldsByProductType();
        return;
    }

    categorySelect.innerHTML = '<option value="">جار التحميل...</option>';

    fetch('/admin/get-categories/' + encodeURIComponent(type))
        .then(response => response.json())
        .then(data => {
            categorySelect.innerHTML = '<option value="">اختر التصنيف</option>';
            if (data.length > 0) {
                data.forEach(cat => {
                    const option = document.createElement('option');
                    option.value = cat.id;
                    option.textContent = cat.name;
                    categorySelect.appendChild(option);
                });
            } else {
                categorySelect.innerHTML = '<option value="">لا توجد تصنيفات</option>';
            }
            toggleFieldsByProductType();
        })
        .catch(error => {
            console.error('خطأ:', error);
            categorySelect.innerHTML = '<option value="">خطأ في التحميل</option>';
        });
}

function calculateTotals() {
    const price = parseFloat(document.getElementById('price').value) || 0;
    const productType = document.getElementById('product_type').value;
    const discountInput = document.getElementById('discount');
    const finalPriceInput = document.getElementById('final_price');
    
    let finalPrice = price;
    
    if (productType === 'أدوية') {
        const discount = parseFloat(discountInput.value) || 0;
        if (discount > 0 && discount <= 100) {
            finalPrice = price - (price * discount / 100);
        }
        finalPriceInput.value = finalPrice.toFixed(2) + ' جنيه';
        
        if (discount > 0) {
            finalPriceInput.style.background = '#e8f5e8';
            finalPriceInput.style.color = '#2d5016';
            finalPriceInput.style.fontWeight = 'bold';
        } else {
            finalPriceInput.style.background = '#f0f0f0';
            finalPriceInput.style.color = '#333';
            finalPriceInput.style.fontWeight = 'normal';
        }
    } else {
        finalPrice = price;
    }

    const unitsPerPackage = parseFloat(document.getElementById('units_per_package').value) || 1;
    const packagePriceInput = document.getElementById('package_price_display');
    const packagePrice = finalPrice * unitsPerPackage;
    packagePriceInput.value = packagePrice.toFixed(2) + ' جنيه';
}

function addIngredient() {
    const container = document.getElementById('ingredients-container');
    const div = document.createElement('div');
    div.className = 'ingredient-row';
    div.innerHTML = `
        <input type="text" name="active_ingredient[]" placeholder="مادة فعالة أخرى">
        <button type="button" class="btn-remove" onclick="removeIngredient(this)"><i class="fas fa-times"></i></button>
    `;
    container.appendChild(div);
}

function removeIngredient(btn) {
    btn.closest('.ingredient-row').remove();
}

function openModal(id) {
    document.getElementById(id).style.display = 'flex';
}

function closeModal(id) {
    document.getElementById(id).style.display = 'none';
    if(id === 'supplierModal') {
        document.getElementById('quickSupplierForm').reset();
        document.getElementById('supplierErrors').style.display = 'none';
    } else {
        document.getElementById('quickCategoryForm').reset();
        document.getElementById('categoryErrors').style.display = 'none';
    }
}

function submitQuickSupplier(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    const errorDiv = document.getElementById('supplierErrors');
    
    fetch('{{ route("admin.products.quick-store-supplier") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const select = document.getElementById('supplier_select');
            const option = document.createElement('option');
            option.value = data.supplier.id;
            option.text = data.supplier.name;
            option.selected = true;
            select.add(option);
            
            closeModal('supplierModal');
            alert('تم إضافة المورد بنجاح');
        } else {
            let errorHtml = '<ul>';
            for (const [key, value] of Object.entries(data.errors)) {
                let msg = Array.isArray(value) ? value[0] : value;
                let arabicMsg = getArabicError(key, msg);
                errorHtml += `<li>${arabicMsg}</li>`;
            }
            errorHtml += '</ul>';
            errorDiv.innerHTML = errorHtml;
            errorDiv.style.display = 'block';
        }
    })
    .catch(error => console.error('Error:', error));
}

function submitQuickCategory(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    const errorDiv = document.getElementById('categoryErrors');
    
    fetch('{{ route("admin.products.quick-store-category") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const currentType = document.getElementById('product_type').value;
            
            if (!currentType) {
                document.getElementById('product_type').value = data.category.product_type;
                toggleFieldsByProductType();
            }
            
            if (document.getElementById('product_type').value === data.category.product_type) {
                const select = document.getElementById('category_id');
                if (select.options.length === 1 && select.options[0].value === "") {
                }
                const option = document.createElement('option');
                option.value = data.category.id;
                option.text = data.category.name;
                option.selected = true;
                select.add(option);
            } else {
                alert('تم إضافة التصنيف بنجاح، لكنه لن يظهر الآن لأن نوع المنتج المختار مختلف.');
            }
            
            closeModal('categoryModal');
        } else {
            let errorHtml = '<ul>';
            for (const [key, value] of Object.entries(data.errors)) {
                let msg = Array.isArray(value) ? value[0] : value;
                let arabicMsg = getArabicError(key, msg);
                errorHtml += `<li>${arabicMsg}</li>`;
            }
            errorHtml += '</ul>';
            errorDiv.innerHTML = errorHtml;
            errorDiv.style.display = 'block';
        }
    })
    .catch(error => console.error('Error:', error));
}

document.addEventListener('DOMContentLoaded', function() {
    toggleFieldsByProductType();
    calculateTotals();

    const priceInput = document.getElementById('price');
    const discountInput = document.getElementById('discount');
    const unitsInput = document.getElementById('units_per_package');

    if (priceInput) priceInput.addEventListener('input', calculateTotals);
    if (unitsInput) unitsInput.addEventListener('input', calculateTotals);
    if (discountInput) {
        discountInput.addEventListener('input', function() {
            if (this.value > 100) this.value = 100;
            if (this.value < 0) this.value = 0;
            calculateTotals();
        });
    }
});
</script>

</body>
</html>
