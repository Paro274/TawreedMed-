<div class="sidebar">
    <h2>القائمة</h2>
    <ul>
        <li><a href="/supplier/dashboard">الرئيسية</a></li>
        <li><a href="/supplier/products">المنتجات</a></li>
        <li><a href="/supplier/products/create">إضافة منتج</a></li>
        <li><a href="{{ route('supplier.orders.index') }}">
                <i class="fas fa-shopping-cart ml-2"></i> إدارة الطلبات
            </a>
        </li>
        <li>
            <a href="{{ route('supplier.orders.index') }}?tab=invoices">
                <i class="fas fa-file-invoice ml-2"></i> الفواتير
            </a>
        </li>
        <li><a href="/supplier/logout"><i class="fas fa-sign-out-alt ml-2"></i> تسجيل الخروج</a></li>
    </ul>
</div>

<style>
    .sidebar {
        background: linear-gradient(135deg, #16a34a, #22c55e);
        color: white;
        width: 220px;
        height: 100vh;
        position: fixed;
        top: 0;
        right: 0;
        padding: 25px 0;
        text-align: center;
        box-shadow: -2px 0 10px rgba(0,0,0,0.15);
    }

    .sidebar h2 {
        font-size: 22px;
        margin-bottom: 25px;
    }

    .sidebar ul {
        list-style: none;
        padding: 0;
    }

    .sidebar ul li {
        margin: 15px 0;
    }

    .sidebar ul li a {
        color: white;
        text-decoration: none;
        background: rgba(255,255,255,0.15);
        padding: 10px 20px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        transition: 0.3s;
        width: 180px;
        margin: 0 auto;
    }
    
    .sidebar ul li a i {
        margin-right: 8px;
    }

    .sidebar ul li a:hover {
        background: rgba(255,255,255,0.3);
    }

    @media (max-width: 768px) {
        .sidebar {
            position: relative;
            width: 100%;
            height: auto;
            box-shadow: none;
        }

        .sidebar ul li a {
            display: block;
            margin: 8px auto;
            max-width: 250px;
        }
    }
</style>
