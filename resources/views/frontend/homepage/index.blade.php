@extends('frontend.layouts.app')

@section('title', 'الصفحة الرئيسية - توريد ميد')

@section('content')
    @include('frontend.homepage.banner')
    @include('frontend.homepage.medicines')
    @include('frontend.homepage.medical-supplies')
    @include('frontend.homepage.cosmetics')
    @include('frontend.homepage.stats')
    @include('frontend.homepage.features')
    @include('frontend.homepage.testimonials')
    @include('frontend.homepage.cta')
    @include('frontend.homepage.contact')
@endsection

@push('scripts')
<script>
function addToCart(productId, minQty = 1) {
    const quantity = minQty;
    
    // Show loading
    const btn = event.target.closest('button');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الإضافة...';
    
    const formData = new FormData();
    formData.append('product_id', productId);
    formData.append('quantity', quantity);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    
    fetch('{{ route("frontend.cart.add") }}', {
        method: 'POST',
        headers: {
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'تم بنجاح!',
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                alert(data.message);
            }
            
            // Update cart counter
            updateCartCounter(data.count);
        } else {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: data.icon || 'error',
                    title: data.icon === 'warning' ? 'تنبيه!' : 'خطأ!',
                    text: data.message
                });
            } else {
                alert(data.message);
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ أثناء إضافة المنتج للسلة');
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = originalText;
    });
}

function updateCartCounter(count) {
    const cartBadge = document.querySelector('.cart-count');
    if (cartBadge) {
        cartBadge.textContent = count;
        if (count > 0) {
            cartBadge.classList.remove('bg-secondary');
            cartBadge.classList.add('bg-danger');
        } else {
            cartBadge.classList.remove('bg-danger');
            cartBadge.classList.add('bg-secondary');
        }
    }
}
</script>
@endpush
