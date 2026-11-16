<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Trang Quản trị - Eiser' }}</title>
    
    <!-- Google Font Plus Jakarta Sans & Eiser Brand CSS -->
    <link rel="stylesheet" href="{{ asset('assets/admin/css/admin-ui.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/clients/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/clients/css/themify-icons.css') }}">

    @yield('styles')
</head>
<body>
    <!-- SIDEBAR QUẢN TRỊ -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('admin.dashboard') }}" class="brand-logo">
                <div class="brand-icon">E</div>
                <div>
                    <span class="brand-text">EISER</span>
                    <span class="brand-tag">ADMIN</span>
                </div>
            </a>
        </div>

        <nav class="sidebar-menu">
            <div class="menu-category">Tổng quan</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                <span>Dashboard</span>
            </a>

            <div class="menu-category">Bán hàng</div>
            <a href="{{ route('admin.orders.index') }}" class="nav-item {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                <span>Đơn hàng</span>
                @if(isset($pendingOrdersCount) && $pendingOrdersCount > 0)
                    <span class="badge badge-amber" style="{{ request()->routeIs('admin.orders*') ? 'background:#fff; color:var(--primary);' : '' }}">{{ $pendingOrdersCount }}</span>
                @endif
            </a>
            <a href="#" class="nav-item">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                <span>Khách hàng</span>
            </a>

            <div class="menu-category">Sản phẩm & Kho</div>
            <a href="{{ route('admin.products.index') }}" class="nav-item {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                <span>Sản phẩm</span>
            </a>
            <a href="#" class="nav-item">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                <span>Danh mục & Hiệu</span>
            </a>

            <div class="menu-category">Hệ thống</div>
            <a href="#" class="nav-item">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                <span>Tin nhắn liên hệ</span>
                @if(isset($unreadContactsCount) && $unreadContactsCount > 0)
                    <span class="badge badge-green">{{ $unreadContactsCount }}</span>
                @endif
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="user-avatar">
                {{ strtoupper(substr(session('admin_name', session('admin', 'AD')), 0, 2)) }}
            </div>
            <div class="user-info">
                <div class="user-name">{{ session('admin_name', 'Quản trị viên') }}</div>
                <div class="user-role">{{ '@' . session('admin', 'admin') }} • Admin</div>
            </div>
        </div>
    </aside>

    <!-- KHUNG CHÍNH -->
    <div class="main-wrapper">
        <!-- TOPBAR -->
        <header class="topbar">
            <div class="topbar-left">
                <div class="search-box">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input type="text" placeholder="Tìm kiếm nhanh đơn hàng, sản phẩm, khách...">
                </div>
            </div>

            <div class="topbar-right">
                <a href="{{ route('home') }}" target="_blank" class="btn-client-preview" title="Xem giao diện khách hàng">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                    <span>Xem Shop (Client)</span>
                </a>
                <a href="{{ route('admin.logout') }}" class="btn-logout" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất trang quản trị?');">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                    <span>Đăng xuất</span>
                </a>
            </div>
        </header>

        <!-- NỘI DUNG TRANG -->
        <main class="content-body">
            @if(session('msg_success'))
                <div style="background: #dcfce7; border: 1px solid #bbf7d0; color: #166534; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; font-size: 13px;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        <strong>{{ session('msg_success') }}</strong>
                    </div>
                    <span style="cursor: pointer;" onclick="this.parentElement.style.display='none';">&times;</span>
                </div>
            @endif

            @if(session('msg_error'))
                <div style="background: #fee2e2; border: 1px solid #fecaca; color: #991b1b; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; font-size: 13px;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                        <strong>{{ session('msg_error') }}</strong>
                    </div>
                    <span style="cursor: pointer;" onclick="this.parentElement.style.display='none';">&times;</span>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @yield('scripts')
</body>
</html>
