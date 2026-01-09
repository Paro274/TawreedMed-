<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>تعديل المنتج - {{ $product->name }}</title>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<script>
function toggleFieldsByProductType() {
    const productType = document.getElementById('product_type').value;
    
    const medicineOnlyFields = document.querySelectorAll('.medicine-only');
    const discountField = document.getElementById('discount-field');
    const priceLabel = document.querySelector('label[for="price"]');
    const finalPriceRow = document.getElementById('final-price-row');
    const packageTypeSelect = document.getElementById('package_type');
    
    // Common fields for all types
    medicineOnlyFields.forEach(field => field.style.display = (productType === 'أدوية') ? 'block' : 'none');
    
    // Discount and final price ONLY for medicines
    if (productType === 'أدوية') {
        discountField.style.display = 'block';
        finalPriceRow.style.display = 'block';
        priceLabel.textContent = 'السعر للقطعة/العلبة (قبل الخصم):';
    } else {
        discountField.style.display = 'none';
        finalPriceRow.style.display = 'none';
        document.getElementById('discount').value = ''; // Reset value
        priceLabel.textContent = 'السعر للجملة:';
    }
    
    if (productType === 'أدوية') {
        packageTypeSelect.innerHTML = `
            <option value="كرتونة" ${packageTypeSelect.dataset.selected == 'كرتونة' ? 'selected' : ''}>كرتونة</option>
            <option value="كيس" ${packageTypeSelect.dataset.selected == 'كيس' ? 'selected' : ''}>كيس</option>
            <option value="علبة" ${packageTypeSelect.dataset.selected == 'علبة' ? 'selected' : ''}>علبة</option>
        `;
    } else {
        packageTypeSelect.innerHTML = `
            <option value="علبة" ${packageTypeSelect.dataset.selected == 'علبة' ? 'selected' : ''}>علبة</option>
            <option value="كيس" ${packageTypeSelect.dataset.selected == 'كيس' ? 'selected' : ''}>كيس</option>
            <option value="دستة" ${packageTypeSelect.dataset.selected == 'دستة' ? 'selected' : ''}>دستة</option>
            <option value="كرتونة" ${packageTypeSelect.dataset.selected == 'كرتونة' ? 'selected' : ''}>كرتونة</option>
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
    fetch('/supplier/get-categories/' + encodeURIComponent(type))
        .then(response => response.json())
        .then(data => {
            categorySelect.innerHTML = '<option value="">اختر التصنيف</option>';
            if (data.length > 0) {
                data.forEach(cat => {
                    const option = document.createElement('option');
                    option.value = cat.id;
                    option.textContent = cat.name;
                    if (option.value == {{ $product->category_id }}) {
                        option.selected = true;
                    }
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
        } else {
            finalPriceInput.style.background = '#f0f0f0';
            finalPriceInput.style.color = '#333';
        }
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

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('product_type').value = '{{ $product->product_type }}';
    loadCategories();
    toggleFieldsByProductType();
    calculateTotals();

    const priceInput = document.getElementById('price');
    const discountInput = document.getElementById('discount');
    const unitsInput = document.getElementById('units_per_package');
    const productTypeSelect = document.getElementById('product_type');

    if (priceInput) priceInput.addEventListener('input', calculateTotals);
    if (unitsInput) unitsInput.addEventListener('input', calculateTotals);
    if (discountInput) {
        discountInput.addEventListener('input', function() {
            if (this.value > 100) this.value = 100;
            if (this.value < 0) this.value = 0;
            calculateTotals();
        });
    }
    if (productTypeSelect) {
        productTypeSelect.addEventListener('change', function() {
            loadCategories();
            toggleFieldsByProductType();
        });
    }
});
</script>

<style>
body { font-family:"Cairo",sans-serif;background:#f2f4f8; }
.container { margin-right:240px; padding:30px; }
.form-box {
    background:white; padding:25px 30px; border-radius:10px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1); max-width:900px; margin:auto;
}
h2 { color:#111827; margin-bottom:20px; }
label { font-weight:600; display:block; margin-bottom:5px; }
input, select, textarea {
    width:100%; padding:10px; margin-bottom:15px;
    border:1px solid #ccc; border-radius:6px; font-family:"Cairo",sans-serif;
}
button {
    background:#4f46e5; color:white; font-weight:600; border:none;
    border-radius:6px; padding:10px 15px; cursor:pointer; transition:0.3s;
}
button:hover { background:#4338ca; }
.file-upload input { padding:6px; background:#fafafa; border:1px dashed #ccc; }
h3 { color: #374151; border-bottom: 2px solid #e5e7eb; padding-bottom: 5px; margin-top: 25px; margin-bottom: 15px; }
#final_price, #package_price_display {
    font-size: 1.1rem; text-align: center; border: 2px solid #ddd;
    background: #f8f9fa; font-weight: bold;
}
#discount-field {
    background: linear-gradient(135deg, #f0fdf4, #dcfce7); padding: 20px;
    border-radius: 12px; border: 2px solid #10b981; margin-bottom: 25px; display: none;
}
.ingredient-row { display: flex; gap: 10px; margin-bottom: 10px; }
.ingredient-row input { flex: 1; margin-bottom: 0; }
.btn-add { background: #10b981; margin-bottom: 15px; display: inline-block; }
.btn-remove { background: #ef4444; padding: 0 15px; }
.current-images { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 15px; }
.current-images img { width: 100px; height: 100px; object-fit: cover; border-radius: 8px; border: 2px solid #e5e7eb; }
.row-2-cols { display: flex; gap: 20px; }
.row-2-cols > div { flex: 1; }
.medicine-only { display: none; }
</style>
</head>
<body>

@include('supplier.sidebar')

<div class="container">
<div class="form-box">
<h2>تعديل المنتج: {{ $product->name }}</h2>

@if(session('success'))<p style="color:green;">{{ session('success') }}</p>@endif

<form method="POST" action="{{ route('supplier.products.update', $product->id) }}" enctype="multipart/form-data">
@csrf
@method('POST')

<label>نوع المنتج:</label>
<select name="product_type" id="product_type" onchange="loadCategories();" required>
<option value="">اختر النوع</option>
<option value="أدوية">أدوية</option>
<option value="مستلزمات طبية">مستلزمات طبية</option>
<option value="منتجات تجميل">منتجات تجميل</option>
</select>

<label>التصنيف:</label>
<select name="category_id" id="category_id" required>
<option value="">اختر التصنيف</option>
</select>

<label>اسم المنتج:</label>
<input type="text" name="name" value="{{ $product->name }}" required>

<label for="price">السعر للقطعة/العلبة (قبل الخصم):</label>
<input type="number" id="price" step="0.01" name="price" value="{{ $product->price }}" required oninput="calculateTotals()">

<div id="discount-field">
    <label>نسبة الخصم (%):</label>
    <input type="number" id="discount" step="0.01" name="discount" value="{{ $product->discount ?? '' }}" oninput="calculateTotals()" min="0" max="100">
</div>

<div id="final-price-row">
    <label>السعر النهائي للوحدة:</label>
    <input type="text" id="final_price" readonly>
</div>

<label>اسم الشركة:</label>
<input type="text" name="company_name" value="{{ $product->company_name }}">

<label>الوصف المختصر:</label>
<textarea class="tiny-editor" name="short_description">{{ $product->short_description }}</textarea>

<label>الوصف التفصيلي:</label>
<textarea class="tiny-editor" name="full_description">{{ $product->full_description }}</textarea>

<h3>المواصفات</h3>

<div class="medicine-only">
    <label>المواد الفعالة:</label>
    <div id="ingredients-container">
        @if(is_array($product->active_ingredient) && count($product->active_ingredient) > 0)
            @foreach($product->active_ingredient as $ingredient)
                <div class="ingredient-row">
                    <input type="text" name="active_ingredient[]" value="{{ $ingredient }}">
                    <button type="button" class="btn-remove" onclick="removeIngredient(this)"><i class="fas fa-times"></i></button>
                </div>
            @endforeach
        @else
            <div class="ingredient-row">
                <input type="text" name="active_ingredient[]" value="{{ is_string($product->active_ingredient) ? $product->active_ingredient : '' }}" placeholder="اسم المادة الفعالة">
            </div>
        @endif
    </div>
    <button type="button" class="btn-add" onclick="addIngredient()">+ إضافة مادة أخرى</button>
</div>

<div class="medicine-only">
    <label>الشكل الدوائي:</label>
    <input type="text" name="dosage_form" value="{{ $product->dosage_form }}">
</div>

<div class="medicine-only">
    <label>عدد أقراص العبوة:</label>
    <input type="number" name="tablets_per_pack" value="{{ $product->tablets_per_pack }}">
</div>

<label>تاريخ الإنتاج (اختياري):</label>
<input type="date" name="production_date" value="{{ $product->production_date ? $product->production_date->format('Y-m-d') : '' }}">

<label>تاريخ الانتهاء (اختياري):</label>
<input type="date" name="expiry_date" value="{{ $product->expiry_date ? $product->expiry_date->format('Y-m-d') : '' }}">

<h3>التعبئة والكميات</h3>
<label>نوع الباكيدج:</label>
<select name="package_type" id="package_type" data-selected="{{ $product->package_type }}">
<option value="كرتونة">كرتونة</option>
<option value="كيس">كيس</option>
<option value="علبة">علبة</option>
</select>

<label>عدد الوحدات داخل الباكيدج:</label>
<input type="number" id="units_per_package" name="units_per_package" value="{{ $product->units_per_package }}" required oninput="calculateTotals()">

<label>سعر الباكيدج بالكامل:</label>
<input type="text" id="package_price_display" readonly>

<div class="row-2-cols">
    <div>
        <label>أقل كمية للطلب (بالوحدة/القطعة):</label>
        <input type="number" name="min_order_quantity" value="{{ $product->min_order_quantity }}">
    </div>
    <div>
        <label>أقل كمية للطلب (بالباكيدج/الكرتونة):</label>
        <input type="number" name="min_order_package" value="{{ $product->min_order_package }}">
    </div>
</div>

<label for="stock_quantity">إجمالي الكمية (Total Quantity):</label>
<input type="number" id="stock_quantity" name="stock_quantity" value="{{ $product->total_units }}" required>

<h3>الصور الحالية</h3>
<div class="current-images">
@foreach(['image_1', 'image_2', 'image_3', 'image_4'] as $image)
    @if($product->$image)
        <img src="{{ asset($product->$image) }}" alt="صورة" title="يمكن استبدالها">
    @endif
@endforeach
</div>

<h3>إضافة صور جديدة (حتى 4)</h3>
<div class="file-upload">
@for($i=1; $i<=4; $i++)
<input type="file" name="image_{{ $i }}">
@endfor
</div>

<button type="submit">تحديث المنتج</button>
</form>
</div>
</div>

</body>
</html>
