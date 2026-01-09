// js/product-details.js

document.addEventListener('DOMContentLoaded', function() {
    
    // Image Gallery
    const mainImage = document.getElementById('mainImage');
    const thumbnails = document.querySelectorAll('.thumbnail-images img');

    thumbnails.forEach(thumb => {
        thumb.addEventListener('click', function() {
            // Remove active class from all
            thumbnails.forEach(t => t.classList.remove('active'));
            // Add active class to clicked
            this.classList.add('active');
            // Change main image
            mainImage.src = this.src.replace('w=150&h=150', 'w=600&h=600');
        });
    });

    // Quantity Controls
    const minusBtn = document.querySelector('.qty-btn.minus');
    const plusBtn = document.querySelector('.qty-btn.plus');
    const qtyInput = document.querySelector('.qty-input');

    if (minusBtn && plusBtn && qtyInput) {
        minusBtn.addEventListener('click', () => {
            let value = parseInt(qtyInput.value);
            if (value > 1) {
                qtyInput.value = value - 1;
            }
        });

        plusBtn.addEventListener('click', () => {
            let value = parseInt(qtyInput.value);
            let max = parseInt(qtyInput.max);
            if (value < max) {
                qtyInput.value = value + 1;
            }
        });

        qtyInput.addEventListener('input', function() {
            let value = parseInt(this.value);
            let min = parseInt(this.min);
            let max = parseInt(this.max);

            if (value < min) this.value = min;
            if (value > max) this.value = max;
        });
    }

    // Add to Cart
    const addToCartBtn = document.querySelector('.btn-add-to-cart');
    if (addToCartBtn) {
        addToCartBtn.addEventListener('click', () => {
            const qty = qtyInput.value;
            const productName = document.querySelector('.product-title').textContent;
            alert(`تم إضافة ${qty} من "${productName}" إلى السلة!`);
        });
    }

    // Wishlist Toggle
    const wishlistBtn = document.querySelector('.btn-wishlist-detail');
    if (wishlistBtn) {
        wishlistBtn.addEventListener('click', function() {
            this.classList.toggle('active');
            this.textContent = this.classList.contains('active') ? '♥' : '♡';
        });
    }

    // Product Tabs
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabPanels = document.querySelectorAll('.tab-panel');

    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const targetTab = this.dataset.tab;

            // Remove active from all
            tabBtns.forEach(b => b.classList.remove('active'));
            tabPanels.forEach(p => p.classList.remove('active'));

            // Add active to clicked
            this.classList.add('active');
            document.getElementById(targetTab).classList.add('active');
        });
    });

    // Quick Add to Cart for Related Products
    const quickAddBtns = document.querySelectorAll('.btn-quick-add');
    quickAddBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const productName = this.closest('.product-card').querySelector('h3').textContent;
            alert(`تم إضافة "${productName}" إلى السلة!`);
        });
    });

    console.log('صفحة تفاصيل المنتج - تم تحميل السكريبتات بنجاح ✓');
});
