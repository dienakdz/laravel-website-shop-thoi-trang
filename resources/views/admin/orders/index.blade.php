@extends('admin.layout')

@section('styles')
<style>
    .tabs-header {
        display: flex;
        gap: 6px;
        border-bottom: 1px solid var(--border-color);
        margin-bottom: 20px;
        overflow-x: auto;
        padding-bottom: 0px;
    }

    .tab-btn {
        background: none;
        border: none;
        padding: 12px 16px;
        font-size: 13px;
        font-weight: 600;
        color: var(--text-muted);
        cursor: pointer;
        border-bottom: 2px solid transparent;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: -1px;
        text-decoration: none;
        transition: all 0.2s;
        white-space: nowrap;
    }

    .tab-btn:hover {
        color: var(--text-main);
    }

    .tab-btn.active {
        color: var(--primary);
        border-bottom-color: var(--primary);
    }

    .tab-count {
        background: #f1f5f9;
        color: #475569;
        padding: 2px 7px;
        border-radius: 10px;
        font-size: 11px;
        font-weight: 700;
    }

    .tab-btn.active .tab-count {
        background: var(--primary-light);
        color: var(--primary);
    }

    .filter-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .filter-left {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .filter-select {
        padding: 8px 12px;
        border: 1px solid var(--border-color);
        border-radius: 6px;
        font-size: 13px;
        background: #ffffff;
        color: var(--text-main);
        outline: none;
    }

    .order-detail-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 20px;
        margin-top: 28px;
    }

    .row-highlight td {
        background-color: rgba(113, 205, 20, 0.06) !important;
    }

    /* Bootstrap 4 custom pagination override */
    .pagination {
        display: flex;
        list-style: none;
        gap: 4px;
        padding: 0;
        margin: 0;
    }
    .pagination li a, .pagination li span {
        padding: 6px 12px;
        border: 1px solid var(--border-color);
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        color: var(--text-main);
        text-decoration: none;
        background: #fff;
    }
    .pagination li.active span {
        background: var(--primary);
        color: #fff;
        border-color: var(--primary);
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Quản lý đơn hàng</h1>
        <p class="page-subtitle">Theo dõi, duyệt đơn và cập nhật tiến trình giao hàng cho khách</p>
    </div>
</div>

<div class="card">
    <!-- TABS LỌC TRẠNG THÁI -->
    <div class="tabs-header">
        <a href="{{ route('admin.orders.index', ['status' => 'all', 'search' => request('search')]) }}" 
           class="tab-btn {{ $currentStatus === 'all' ? 'active' : '' }}">
            <span>Tất cả</span>
            <span class="tab-count">{{ $allCount }}</span>
        </a>
        <a href="{{ route('admin.orders.index', ['status' => '1', 'search' => request('search')]) }}" 
           class="tab-btn {{ $currentStatus === '1' ? 'active' : '' }}">
            <span>Chờ xác nhận</span>
            <span class="tab-count" style="background:#fef3c7; color:#92400e;">{{ $pendingCount }}</span>
        </a>
        <a href="{{ route('admin.orders.index', ['status' => '2', 'search' => request('search')]) }}" 
           class="tab-btn {{ $currentStatus === '2' ? 'active' : '' }}">
            <span>Đang chuẩn bị</span>
            <span class="tab-count" style="background:#dbeafe; color:#1e40af;">{{ $preparingCount }}</span>
        </a>
        <a href="{{ route('admin.orders.index', ['status' => '3', 'search' => request('search')]) }}" 
           class="tab-btn {{ $currentStatus === '3' ? 'active' : '' }}">
            <span>Đang giao hàng</span>
            <span class="tab-count" style="background:#f3e8ff; color:#6b21a8;">{{ $shippingCount }}</span>
        </a>
        <a href="{{ route('admin.orders.index', ['status' => '4', 'search' => request('search')]) }}" 
           class="tab-btn {{ $currentStatus === '4' ? 'active' : '' }}">
            <span>Hoàn thành</span>
            <span class="tab-count" style="background:#dcfce7; color:#166534;">{{ $completedCount }}</span>
        </a>
        <a href="{{ route('admin.orders.index', ['status' => '5', 'search' => request('search')]) }}" 
           class="tab-btn {{ $currentStatus === '5' ? 'active' : '' }}">
            <span>Đã hủy</span>
            <span class="tab-count" style="background:#fee2e2; color:#991b1b;">{{ $cancelledCount }}</span>
        </a>
    </div>

    <!-- THANH CÔNG CỤ TÌM KIẾM & BỘ LỌC -->
    <form method="GET" action="{{ route('admin.orders.index') }}" class="filter-toolbar">
        <input type="hidden" name="status" value="{{ $currentStatus }}">
        <div class="filter-left">
            <div style="position: relative; width: 280px;">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Mã đơn #DH, tên khách, SĐT..." class="filter-select" style="width: 100%; padding-left: 32px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%);">
                    <circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </div>

            <select name="shippingtype" class="filter-select" onchange="this.form.submit()">
                <option value="all">Phương thức: Tất cả</option>
                <option value="nhận hàng" {{ request('shippingtype') == 'nhận hàng' ? 'selected' : '' }}>COD (Khi nhận hàng)</option>
                <option value="PayPal" {{ request('shippingtype') == 'PayPal' ? 'selected' : '' }}>PayPal</option>
                <option value="khoản" {{ request('shippingtype') == 'khoản' ? 'selected' : '' }}>Chuyển khoản</option>
            </select>

            <select name="time" class="filter-select" onchange="this.form.submit()">
                <option value="all">Thời gian: Tất cả</option>
                <option value="today" {{ request('time') == 'today' ? 'selected' : '' }}>Hôm nay</option>
                <option value="this_month" {{ request('time') == 'this_month' ? 'selected' : '' }}>Tháng này</option>
            </select>

            <button type="submit" class="btn-secondary" style="padding: 7px 12px;">Lọc</button>
            @if(request()->hasAny(['search', 'shippingtype', 'time']))
                <a href="{{ route('admin.orders.index', ['status' => $currentStatus]) }}" class="btn-secondary" style="color: #64748b;">Xóa lọc</a>
            @endif
        </div>

        <div style="font-size: 13px; color: var(--text-muted);">
            Hiển thị <strong>{{ $orders->count() }}</strong> trên tổng <strong>{{ $orders->total() }}</strong> đơn hàng
        </div>
    </form>

    <!-- BẢNG DANH SÁCH ĐƠN HÀNG -->
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Mã đơn</th>
                    <th>Khách hàng & SĐT</th>
                    <th>Địa chỉ giao hàng</th>
                    <th>Ngày đặt</th>
                    <th>Tổng tiền</th>
                    <th>Phương thức</th>
                    <th>Trạng thái</th>
                    <th style="text-align: right;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr class="{{ ($previewOrder && $previewOrder->OrderID == $order->OrderID) ? 'row-highlight' : '' }}">
                        <td>
                            <a href="{{ route('admin.orders.index', array_merge(request()->query(), ['preview_id' => $order->OrderID])) }}#order-preview" 
                               style="text-decoration: none; color: #0f172a; font-weight: 700; font-size: 13px;">
                                #DH-{{ str_pad($order->OrderID, 4, '0', STR_PAD_LEFT) }}
                            </a>
                        </td>
                        <td>
                            <div style="font-weight: 600; color: #0f172a;">{{ $order->Fulname ?? 'Khách vãng lai' }}</div>
                            <div style="font-size: 12px; color: #64748b;">{{ $order->Phonenumber ?? '---' }}</div>
                        </td>
                        <td>
                            <div style="font-size: 13px; max-width: 230px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $order->Address }}">
                                {{ $order->Address ?? 'Chưa cung cấp' }}
                            </div>
                        </td>
                        <td style="font-size: 12px; color: #64748b;">
                            {{ $order->OrderDate }}
                        </td>
                        <td>
                            <strong style="color: #0f172a; font-size: 13px;">{{ number_format($order->Totalprice, 0, ',', '.') }} ₫</strong>
                        </td>
                        <td>
                            <span style="font-size: 12px; color: #475569;">{{ $order->Shippingtype ?? 'COD' }}</span>
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
                            <div style="display: inline-flex; align-items: center; gap: 6px;">
                                @if($order->Status == '1')
                                    <form action="{{ route('admin.orders.update-status', $order->OrderID) }}" method="POST" style="margin: 0;">
                                        @csrf
                                        <input type="hidden" name="status" value="2">
                                        <input type="hidden" name="current_tab" value="{{ $currentStatus }}">
                                        <button type="submit" class="btn-primary" style="padding: 5px 10px; font-size: 12px;" title="Xác nhận duyệt đơn">
                                            Duyệt đơn
                                        </button>
                                    </form>
                                @elseif($order->Status == '2')
                                    <form action="{{ route('admin.orders.update-status', $order->OrderID) }}" method="POST" style="margin: 0;">
                                        @csrf
                                        <input type="hidden" name="status" value="3">
                                        <input type="hidden" name="current_tab" value="{{ $currentStatus }}">
                                        <button type="submit" class="btn-secondary" style="padding: 5px 10px; font-size: 12px;" title="Chuyển sang giao hàng">
                                            Giao hàng
                                        </button>
                                    </form>
                                @elseif($order->Status == '3')
                                    <form action="{{ route('admin.orders.update-status', $order->OrderID) }}" method="POST" style="margin: 0;">
                                        @csrf
                                        <input type="hidden" name="status" value="4">
                                        <input type="hidden" name="current_tab" value="{{ $currentStatus }}">
                                        <button type="submit" class="btn-secondary" style="padding: 5px 10px; font-size: 12px; color: #166534;" title="Hoàn tất đơn hàng">
                                            Hoàn tất
                                        </button>
                                    </form>
                                @endif

                                <a href="{{ route('admin.orders.index', array_merge(request()->query(), ['preview_id' => $order->OrderID])) }}#order-preview" 
                                   class="btn-icon-sm" title="Xem chi tiết đơn">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                </a>

                                <a href="{{ route('admin.orders.print', $order->OrderID) }}" target="_blank" 
                                   class="btn-icon-sm" title="In phiếu / Hóa đơn">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 36px; color: #94a3b8;">
                            Không tìm thấy đơn hàng nào phù hợp với bộ lọc hiện tại.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- PHÂN TRANG -->
    @if($orders->hasPages())
        <div style="display: flex; justify-content: flex-end; margin-top: 20px;">
            {{ $orders->links() }}
        </div>
    @endif

    <!-- KHUNG XEM NHANH CHI TIẾT ĐƠN HÀNG TRỰC TIẾP TRÊN TRANG -->
    @if($previewOrder)
        <div class="order-detail-card" id="order-preview">
            <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #e2e8f0; padding-bottom: 14px; margin-bottom: 18px; flex-wrap: wrap; gap: 12px;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <span style="font-size: 15px; font-weight: 700; color: #0f172a;">
                        👁️ Chi tiết đơn hàng: <span style="color: var(--primary);">#DH-{{ str_pad($previewOrder->OrderID, 4, '0', STR_PAD_LEFT) }}</span>
                    </span>
                    @if($previewOrder->Status == '1')
                        <span class="status-pill status-pending"><span class="dot"></span>Chờ xác nhận</span>
                    @elseif($previewOrder->Status == '2')
                        <span class="status-pill status-preparing"><span class="dot"></span>Đang chuẩn bị</span>
                    @elseif($previewOrder->Status == '3')
                        <span class="status-pill status-shipping"><span class="dot"></span>Đang giao</span>
                    @elseif($previewOrder->Status == '4')
                        <span class="status-pill status-completed"><span class="dot"></span>Hoàn thành</span>
                    @else
                        <span class="status-pill status-cancelled"><span class="dot"></span>Đã hủy đơn</span>
                    @endif
                </div>

                <!-- FORM ĐỔI TRẠNG THÁI TRỰC TIẾP -->
                <form action="{{ route('admin.orders.update-status', $previewOrder->OrderID) }}" method="POST" style="display: flex; align-items: center; gap: 8px; margin: 0;">
                    @csrf
                    <input type="hidden" name="current_tab" value="{{ $currentStatus }}">
                    <span style="font-size: 13px; font-weight: 600; color: #475569;">Cập nhật trạng thái:</span>
                    <select name="status" class="filter-select" style="font-weight: 600;">
                        <option value="1" {{ $previewOrder->Status == '1' ? 'selected' : '' }}>1. Chờ xác nhận</option>
                        <option value="2" {{ $previewOrder->Status == '2' ? 'selected' : '' }}>2. Đang chuẩn bị hàng</option>
                        <option value="3" {{ $previewOrder->Status == '3' ? 'selected' : '' }}>3. Đang giao hàng</option>
                        <option value="4" {{ $previewOrder->Status == '4' ? 'selected' : '' }}>4. Hoàn thành</option>
                        <option value="5" {{ $previewOrder->Status == '5' ? 'selected' : '' }}>5. Hủy đơn hàng</option>
                    </select>
                    <button type="submit" class="btn-primary" style="padding: 7px 14px;">Lưu</button>
                    <a href="{{ route('admin.orders.print', $previewOrder->OrderID) }}" target="_blank" class="btn-secondary" style="padding: 7px 12px;" title="In hóa đơn">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                        <span>In phiếu</span>
                    </a>
                </form>
            </div>

            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
                <!-- DANH SÁCH SẢN PHẨM TRONG ĐƠN -->
                <div>
                    <div style="font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 10px;">
                        Danh sách sản phẩm trong đơn ({{ $previewItems->count() }} mặt hàng)
                    </div>
                    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; overflow: hidden;">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Đơn giá</th>
                                    <th>Số lượng</th>
                                    <th>Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($previewItems as $item)
                                    <tr>
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 10px;">
                                                <div style="width: 40px; height: 40px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0;">
                                                    @if($item->Picture && file_exists(public_path('assets/admin/img/upload/' . $item->Picture)))
                                                        <img src="{{ asset('assets/admin/img/upload/' . $item->Picture) }}" alt="{{ $item->ProductName }}" style="width: 100%; height: 100%; object-fit: cover;">
                                                    @else
                                                        <span style="font-size: 18px;">👕</span>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div style="font-weight: 600; font-size: 13px; color: #0f172a;">{{ $item->ProductName ?? 'Sản phẩm mã #' . $item->ProductID }}</div>
                                                    <div style="font-size: 11px; color: #64748b;">Mã SP: #SP-{{ $item->ProductID }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ number_format($item->Price / max(1, $item->QuantityOrdered), 0, ',', '.') }} ₫</td>
                                        <td><strong>x{{ $item->QuantityOrdered }}</strong></td>
                                        <td><strong>{{ number_format($item->Price, 0, ',', '.') }} ₫</strong></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" style="text-align: center; padding: 20px; color: #94a3b8;">
                                            Chưa có chi tiết sản phẩm cho đơn hàng này.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- THÔNG TIN GIAO NHẬN & THANH TOÁN -->
                <div style="background: #ffffff; padding: 18px; border-radius: 6px; border: 1px solid #e2e8f0;">
                    <div style="font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 12px; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px;">
                        Thông tin giao nhận
                    </div>
                    <div style="font-size: 13px; line-height: 1.8; color: #475569;">
                        <div><strong>Người nhận:</strong> {{ $previewOrder->Fulname ?? 'Khách vãng lai' }}</div>
                        <div><strong>Số điện thoại:</strong> {{ $previewOrder->Phonenumber ?? '---' }}</div>
                        <div><strong>Địa chỉ:</strong> {{ $previewOrder->Address ?? '---' }}</div>
                        <div><strong>Phương thức:</strong> {{ $previewOrder->Shippingtype ?? 'COD' }}</div>
                        <div><strong>Thời gian đặt:</strong> {{ $previewOrder->OrderDate }}</div>

                        <div style="border-top: 1px solid #f1f5f9; margin-top: 14px; padding-top: 12px; font-size: 14px; display: flex; align-items: center; justify-content: space-between;">
                            <strong>Tổng thanh toán:</strong>
                            <span style="color: #0f172a; font-weight: 700; font-size: 17px;">
                                {{ number_format($previewOrder->Totalprice, 0, ',', '.') }} ₫
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
