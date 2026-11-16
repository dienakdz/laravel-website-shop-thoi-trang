@extends('admin.layout')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Tổng quan kinh doanh</h1>
        <p class="page-subtitle">Cập nhật theo dữ liệu thực tế hệ thống • Chúc bạn một ngày làm việc hiệu quả!</p>
    </div>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('home') }}" target="_blank" class="btn-secondary">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
            <span>Xem cửa hàng</span>
        </a>
        <a href="{{ route('admin.products.create') }}" class="btn-primary">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            <span>Thêm sản phẩm</span>
        </a>
    </div>
</div>

<!-- 4 THẺ CHỈ SỐ DOANH THU & KINH DOANH -->
<div class="stats-grid">
    <div class="card stat-card">
        <div>
            <div class="stat-label">Doanh thu ghi nhận</div>
            <div class="stat-value">{{ number_format($totalRevenue, 0, ',', '.') }} ₫</div>
            <div class="stat-trend up">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>
                <span>Tổng tích lũy đơn hàng</span>
            </div>
        </div>
        <div class="stat-icon">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
        </div>
    </div>

    <div class="card stat-card amber">
        <div>
            <div class="stat-label">Đơn hàng cần xử lý</div>
            <div class="stat-value">{{ $pendingOrdersCount }} đơn mới</div>
            <div class="stat-trend" style="color: #d97706;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                <span>Tổng số: {{ $totalOrdersCount }} đơn đã đặt</span>
            </div>
        </div>
        <div class="stat-icon amber">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
        </div>
    </div>

    <div class="card stat-card blue">
        <div>
            <div class="stat-label">Sản phẩm đang bán</div>
            <div class="stat-value">{{ $totalProductsCount }} mặt hàng</div>
            <div class="stat-trend subtle">
                @if($lowStockCount > 0)
                    <span style="color: #ea580c; font-weight: 600;">⚠️ Có {{ $lowStockCount }} sản phẩm sắp hết</span>
                @else
                    <span>Kho hàng ổn định</span>
                @endif
            </div>
        </div>
        <div class="stat-icon blue">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg>
        </div>
    </div>

    <div class="card stat-card purple">
        <div>
            <div class="stat-label">Khách hàng thành viên</div>
            <div class="stat-value">{{ $totalCustomersCount }} tài khoản</div>
            <div class="stat-trend up">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>
                <span>Đã kích hoạt trên hệ thống</span>
            </div>
        </div>
        <div class="stat-icon purple">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
        </div>
    </div>
</div>

<!-- 2 CỘT: ĐƠN HÀNG MỚI NHẤT & TOP SẢN PHẨM -->
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
    <!-- BẢNG ĐƠN HÀNG MỚI NHẤT -->
    <div class="card">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 18px;">
            <div>
                <h2 style="font-size: 16px; font-weight: 700; color: #1e293b;">Đơn hàng mới nhất cần duyệt</h2>
                <p style="font-size: 12px; color: #64748b;">Khách hàng vừa đặt qua website hôm nay</p>
            </div>
            <a href="{{ route('admin.orders.index') }}" style="font-size: 13px; font-weight: 600; color: var(--primary); text-decoration: none;">Xem tất cả đơn &rarr;</a>
        </div>

        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Mã đơn</th>
                        <th>Khách hàng</th>
                        <th>Thời gian</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th style="text-align: right;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                        <tr>
                            <td>
                                <strong style="color: #0f172a; font-size: 13px;">#DH-{{ str_pad($order->OrderID, 4, '0', STR_PAD_LEFT) }}</strong>
                            </td>
                            <td>
                                <div style="font-weight: 600; color: #1e293b; font-size: 13px;">{{ $order->Fulname ?? 'Khách vãng lai' }}</div>
                                <div style="font-size: 11px; color: #64748b;">{{ $order->Phonenumber ?? '---' }}</div>
                            </td>
                            <td style="color: #64748b; font-size: 12px;">
                                {{ $order->OrderDate }}
                            </td>
                            <td>
                                <strong style="color: #0f172a; font-size: 13px;">{{ number_format($order->Totalprice, 0, ',', '.') }} ₫</strong>
                            </td>
                            <td>
                                @if($order->Status == '1')
                                    <span class="status-pill status-pending"><span class="dot"></span>Chờ xác nhận</span>
                                @elseif($order->Status == '2')
                                    <span class="status-pill status-preparing"><span class="dot"></span>Đang chuẩn bị</span>
                                @elseif($order->Status == '3')
                                    <span class="status-pill status-shipping"><span class="dot"></span>Đang giao</span>
                                @elseif($order->Status == '4')
                                    <span class="status-pill status-completed"><span class="dot"></span>Hoàn thành</span>
                                @else
                                    <span class="status-pill status-cancelled"><span class="dot"></span>Đã hủy</span>
                                @endif
                            </td>
                            <td style="text-align: right;">
                                @if($order->Status == '1')
                                    <form action="{{ route('admin.quick-approve', $order->OrderID) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        <button type="submit" class="btn-primary" style="padding: 5px 10px; font-size: 12px;">Duyệt đơn</button>
                                    </form>
                                @else
                                    <span style="font-size: 12px; color: #94a3b8;">Đã tiếp nhận</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 24px; color: #94a3b8;">
                                Hiện chưa có đơn hàng nào được ghi nhận.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- TOP SẢN PHẨM BÁN CHẠY -->
    <div class="card">
        <div style="margin-bottom: 18px;">
            <h2 style="font-size: 16px; font-weight: 700; color: #1e293b;">Top sản phẩm nổi bật</h2>
            <p style="font-size: 12px; color: #64748b;">Mặt hàng được quan tâm nhiều nhất</p>
        </div>

        <div style="display: flex; flex-direction: column; gap: 14px;">
            @forelse($topProducts as $prod)
                <div style="display: flex; align-items: center; gap: 12px; padding-bottom: 12px; border-bottom: 1px solid #f1f5f9;">
                    <div style="width: 44px; height: 44px; border-radius: 6px; background: #f8fafc; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0;">
                        @if($prod->Picture && file_exists(public_path('assets/admin/img/upload/' . $prod->Picture)))
                            <img src="{{ asset('assets/admin/img/upload/' . $prod->Picture) }}" alt="{{ $prod->ProductName }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <span style="font-size: 20px;">👕</span>
                        @endif
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <div style="font-weight: 600; font-size: 13px; color: #1e293b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $prod->ProductName }}">
                            {{ $prod->ProductName }}
                        </div>
                        <div style="font-size: 12px; color: #64748b;">
                            {{ $prod->CategoryName ?? 'Thời trang' }} • {{ number_format($prod->Price, 0, ',', '.') }} ₫
                        </div>
                    </div>
                    <div style="text-align: right; flex-shrink: 0;">
                        <div style="font-weight: 700; font-size: 13px; color: #0f172a;">
                            {{ $prod->total_sold }} bán
                        </div>
                        <div style="font-size: 11px; color: {{ $prod->Quantity <= 3 ? '#d97706' : '#10b981' }}; font-weight: 600;">
                            Còn {{ $prod->Quantity }}
                        </div>
                    </div>
                </div>
            @empty
                <div style="text-align: center; padding: 20px; color: #94a3b8;">
                    Chưa có dữ liệu sản phẩm bán ra.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
