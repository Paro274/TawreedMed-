// js/profile.js

// Sample orders data
const ordersData = [
    {
        id: 'ORD-12345',
        date: '2025-11-08',
        status: 'completed',
        items: [
            { name: 'باراسيتامول 500 ملجم', company: 'إيفا فارما', qty: 2, image: 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=80&h=80&fit=crop' },
            { name: 'فيتامين د 5000 وحدة', company: 'ماركيرل', qty: 1, image: 'https://images.unsplash.com/photo-1607619056574-7b8d3ee536b2?w=80&h=80&fit=crop' }
        ],
        total: 480
    },
    {
        id: 'ORD-12344',
        date: '2025-11-05',
        status: 'pending',
        items: [
            { name: 'أموكسيسيللين 1 جم', company: 'فاركو', qty: 3, image: 'https://images.unsplash.com/photo-1471864190281-a93a3070b6de?w=80&h=80&fit=crop' }
        ],
        total: 840
    },
    {
        id: 'ORD-12343',
        date: '2025-11-01',
        status: 'completed',
        items: [
            { name: 'أوميبرازول 20 ملجم', company: 'إيبيكو', qty: 2, image: 'https://images.unsplash.com/photo-1587854692152-cbe660dbde88?w=80&h=80&fit=crop' }
        ],
        total: 390
    },
    {
        id: 'ORD-12342',
        date: '2025-10-28',
        status: 'cancelled',
        items: [
            { name: 'ايبوبروفين 400 ملجم', company: 'إيفا فارما', qty: 1, image: 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=80&h=80&fit=crop' }
        ],
        total: 165
    },
    {
        id: 'ORD-12341',
        date: '2025-10-25',
        status: 'completed',
        items: [
            { name: 'ميتفورمين 500 ملجم', company: 'جلوبال نابى', qty: 2, image: 'https://images.unsplash.com/photo-1550572017-edd951aa8f29?w=80&h=80&fit=crop' }
        ],
        total: 440
    }
];

document.addEventListener('DOMContentLoaded', function() {
    initializeTabs();
    initializeProfileForm();
    renderOrders(ordersData);
    initializeOrderFilter();
});

// Tabs functionality
function initializeTabs() {
    const navItems = document.querySelectorAll('.nav-item');
    const tabContents = document.querySelectorAll('.tab-content');

    navItems.forEach(item => {
        item.addEventListener('click', function() {
            const tabId = this.dataset.tab;

            // Remove active class from all
            navItems.forEach(nav => nav.classList.remove('active'));
            tabContents.forEach(tab => tab.classList.remove('active'));

            // Add active class to clicked
            this.classList.add('active');
            document.getElementById(tabId + 'Tab').classList.add('active');
        });
    });
}

// Profile form edit functionality
function initializeProfileForm() {
    const editBtn = document.getElementById('editBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const profileForm = document.getElementById('profileForm');
    const formInputs = profileForm.querySelectorAll('input, select, textarea');
    const formActions = document.querySelector('.form-actions');

    editBtn.addEventListener('click', function() {
        // Enable inputs
        formInputs.forEach(input => input.disabled = false);
        formActions.style.display = 'flex';
        this.style.display = 'none';
    });

    cancelBtn.addEventListener('click', function() {
        // Disable inputs
        formInputs.forEach(input => input.disabled = true);
        formActions.style.display = 'none';
        editBtn.style.display = 'block';
        profileForm.reset();
    });

    profileForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Save changes logic here
        alert('تم حفظ التغييرات بنجاح!');
        
        // Disable inputs
        formInputs.forEach(input => input.disabled = true);
        formActions.style.display = 'none';
        editBtn.style.display = 'block';
    });
}

// Render orders
function renderOrders(orders) {
    const container = document.getElementById('ordersList');
    
    if (orders.length === 0) {
        container.innerHTML = '<p style="text-align: center; color: #64748b; padding: 40px;">لا توجد طلبات</p>';
        return;
    }

    container.innerHTML = orders.map(order => {
        const statusText = {
            pending: 'قيد التنفيذ',
            completed: 'مكتملة',
            cancelled: 'ملغية'
        };

        return `
            <div class="order-card">
                <div class="order-header">
                    <div>
                        <div class="order-number">#${order.id}</div>
                        <div class="order-date">${formatDate(order.date)}</div>
                    </div>
                    <span class="order-status ${order.status}">${statusText[order.status]}</span>
                </div>

                <div class="order-items">
                    ${order.items.map(item => `
                        <div class="order-item">
                            <div class="order-item-img">
                                <img src="${item.image}" alt="${item.name}">
                            </div>
                            <div class="order-item-info">
                                <h4>${item.name}</h4>
                                <p>${item.company}</p>
                            </div>
                            <div class="order-item-qty">× ${item.qty}</div>
                        </div>
                    `).join('')}
                </div>

                <div class="order-footer">
                    <div class="order-total">الإجمالي: ${order.total} جنيه</div>
                    <div class="order-actions">
                        <button class="btn-view-order" onclick="viewOrder('${order.id}')">عرض التفاصيل</button>
                        ${order.status === 'completed' ? '<button class="btn-reorder" onclick="reorder(\'' + order.id + '\')">إعادة الطلب</button>' : ''}
                    </div>
                </div>
            </div>
        `;
    }).join('');
}

// Order filter
function initializeOrderFilter() {
    const filterSelect = document.getElementById('orderFilter');
    
    filterSelect.addEventListener('change', function() {
        const filterValue = this.value;
        
        if (filterValue === 'all') {
            renderOrders(ordersData);
        } else {
            const filtered = ordersData.filter(order => order.status === filterValue);
            renderOrders(filtered);
        }
    });
}

// Helper functions
function formatDate(dateString) {
    const date = new Date(dateString);
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return date.toLocaleDateString('ar-EG', options);
}

function viewOrder(orderId) {
    alert(`عرض تفاصيل الطلب ${orderId}`);
    // window.location.href = `order-details.html?id=${orderId}`;
}

function reorder(orderId) {
    if (confirm('هل تريد إعادة طلب نفس المنتجات؟')) {
        alert('تم إضافة المنتجات إلى السلة!');
        // window.location.href = 'cart.html';
    }
}

function logout() {
    if (confirm('هل أنت متأكد من تسجيل الخروج؟')) {
        alert('تم تسجيل الخروج بنجاح');
        window.location.href = 'index.html';
    }
}

console.log('صفحة البروفايل - تم تحميل السكريبتات بنجاح ✓');
