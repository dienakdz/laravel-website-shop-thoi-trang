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

    /* MODAL POPUP CHI TIẾT ĐƠN HÀNG */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.65);
        backdrop-filter: blur(2px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 999;
        padding: 20px;
        opacity: 0;
        transition: opacity 0.2s ease-in-out;
    }

    .modal-overlay.show {
        display: flex;
        opacity: 1;
    }

    .order-modal-dialog {
        background: #ffffff;
        border-radius: 12px;
        width: 100%;
        max-width: 920px;
        max-height: 90vh;
        display: flex;
        flex-direction: column;
        box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25);
        overflow: hidden;
        animation: modalIn 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes modalIn {
        from {
            transform: scale(0.96) translateY(12px);
            opacity: 0;
        }
        to {
            transform: scale(1) translateY(0);
            opacity: 1;
        }
    }

    .order-modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 24px;
        border-bottom: 1px solid #e2e8f0;
        background: #ffffff;
    }

    .order-modal-close {
        background: none;
        border: none;
        font-size: 24px;
        color: #94a3b8;
        cursor: pointer;
        line-height: 1;
        padding: 4px;
        border-radius: 6px;
        transition: all 0.15s;
    }

    .order-modal-close:hover {
        color: #0f172a;
        background: #f1f5f9;
    }

    .order-modal-body {
        padding: 22px 24px;
        overflow-y: auto;
        max-height: calc(90vh - 140px);
    }

    .order-modal-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 24px;
        border-top: 1px solid #e2e8f0;
        background: #f8fafc;
        flex-wrap: wrap;
        gap: 12px;
    }

    .modal-spinner {
        display: inline-block;
        width: 28px;
        height: 28px;
        border: 3px solid #e2e8f0;
        border-top-color: var(--primary);
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .badge-payment {
        display: inline-block;
        padding: 2px 8px;
        background: #f1f5f9;
        color: #334155;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
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
                    <tr>
                        <td>
                            <a href="javascript:void(0)" onclick="openOrderModal({{ $order->OrderID }})" 
                               style="text-decoration: none; color: var(--primary); font-weight: 700; font-size: 13px;" title="Xem chi tiết đơn hàng">
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

                                <button type="button" onclick="openOrderModal({{ $order->OrderID }})" 
                                   class="btn-icon-sm" title="Xem chi tiết đơn">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                </button>

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
</div>

<!-- MODAL POPUP CHI TIẾT ĐƠN HÀNG -->
<div class="modal-overlay {{ $previewOrder ? 'show' : '' }}" id="orderDetailModal" onclick="handleOverlayClick(event)">
    <div class="order-modal-dialog">
        <!-- MODAL HEADER -->
        <div class="order-modal-header">
            <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                <span style="font-size: 16px; font-weight: 700; color: #0f172a;" id="modalOrderCode">
                    Chi tiết đơn hàng {{ $previewOrder ? '#DH-' . str_pad($previewOrder->OrderID, 4, '0', STR_PAD_LEFT) : '' }}
                </span>
                <span id="modalStatusBadge" class="status-pill {{ $previewOrder ? ($previewOrder->Status == '1' ? 'status-pending' : ($previewOrder->Status == '2' ? 'status-preparing' : ($previewOrder->Status == '3' ? 'status-shipping' : ($previewOrder->Status == '4' ? 'status-completed' : 'status-cancelled')))) : 'status-pending' }}">
                    <span class="dot"></span><span id="modalStatusText">
                        @if($previewOrder)
                            @if($previewOrder->Status == '1') Chờ xác nhận
                            @elseif($previewOrder->Status == '2') Đang chuẩn bị
                            @elseif($previewOrder->Status == '3') Đang giao hàng
                            @elseif($previewOrder->Status == '4') Hoàn thành
                            @else Đã hủy
                            @endif
                        @else
                            Chờ xác nhận
                        @endif
                    </span>
                </span>
            </div>
            <button type="button" class="order-modal-close" onclick="closeOrderModal()" aria-label="Đóng">&times;</button>
        </div>

        <!-- MODAL BODY -->
        <div class="order-modal-body">
            <!-- Loading Indicator -->
            <div id="modalLoading" style="display: none; text-align: center; padding: 48px 20px; color: #64748b;">
                <div class="modal-spinner"></div>
                <div style="margin-top: 12px; font-size: 13px; font-weight: 500;">Đang tải chi tiết đơn hàng...</div>
            </div>

            <!-- Content Area -->
            <div id="modalContent" style="display: {{ $previewOrder ? 'grid' : 'none' }}; grid-template-columns: 1.55fr 1fr; gap: 20px;">
                <!-- DANH SÁCH SẢN PHẨM TRONG ĐƠN -->
                <div>
                    <div style="font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 10px; display: flex; align-items: center; justify-content: space-between;">
                        <span>📦 Sản phẩm trong đơn</span>
                        <span id="modalItemCount" style="color: #64748b; font-weight: 600; font-size: 12px;">({{ $previewItems->count() }} món)</span>
                    </div>
                    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden;">
                        <table class="admin-table" style="margin: 0;">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th style="text-align: right;">Đơn giá</th>
                                    <th style="text-align: center;">SL</th>
                                    <th style="text-align: right;">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody id="modalItemsTableBody">
                                @if($previewOrder)
                                    @forelse($previewItems as $item)
                                        <tr>
                                            <td>
                                                <div style="display: flex; align-items: center; gap: 10px;">
                                                    <div style="width: 40px; height: 40px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0;">
                                                        @if($item->Picture && file_exists(public_path('assets/admin/img/upload/' . $item->Picture)))
                                                            <img src="{{ asset('assets/admin/img/upload/' . $item->Picture) }}" alt="{{ $item->ProductName }}" style="width: 100%; height: 100%; object-fit: cover;">
                                                        @elseif($item->Picture && file_exists(public_path('assets/clients/img/product/' . $item->Picture)))
                                                            <img src="{{ asset('assets/clients/img/product/' . $item->Picture) }}" alt="{{ $item->ProductName }}" style="width: 100%; height: 100%; object-fit: cover;">
                                                        @else
                                                            <span style="font-size: 18px;">👕</span>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <div style="font-weight: 600; font-size: 13px; color: #0f172a;">{{ $item->ProductName ?? ('Sản phẩm #' . $item->ProductID) }}</div>
                                                        <div style="font-size: 11px; color: #64748b;">Mã SP: #SP-{{ $item->ProductID }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td style="text-align: right; font-size: 13px; color: #334155;">{{ number_format((int)round($item->Price / max(1, $item->QuantityOrdered)), 0, ',', '.') }} ₫</td>
                                            <td style="text-align: center; font-size: 13px;"><strong>x{{ $item->QuantityOrdered }}</strong></td>
                                            <td style="text-align: right; font-size: 13px; font-weight: 700; color: #0f172a;">{{ number_format($item->Price, 0, ',', '.') }} ₫</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" style="text-align: center; padding: 24px; color: #94a3b8;">Chưa có chi tiết sản phẩm cho đơn hàng này.</td>
                                        </tr>
                                    @endforelse
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- THÔNG TIN GIAO NHẬN & THANH TOÁN -->
                <div>
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 18px;">
                        <div style="font-size: 13px; font-weight: 700; color: #1e293b; margin-bottom: 12px; border-bottom: 1px solid #e2e8f0; padding-bottom: 8px; display: flex; align-items: center; gap: 6px;">
                            <span>📍 Thông tin giao nhận</span>
                        </div>
                        <div style="font-size: 13px; line-height: 1.8; color: #475569;">
                            <div><strong style="color: #0f172a;">Người nhận:</strong> <span id="modalCustomerName">{{ $previewOrder->Fulname ?? 'Khách vãng lai' }}</span></div>
                            <div><strong style="color: #0f172a;">Số điện thoại:</strong> <span id="modalCustomerPhone">
                                @if($previewOrder && $previewOrder->Phonenumber && $previewOrder->Phonenumber !== '---')
                                    <a href="tel:{{ $previewOrder->Phonenumber }}" style="color: var(--primary); text-decoration: none; font-weight: 600;">{{ $previewOrder->Phonenumber }}</a>
                                @else
                                    ---
                                @endif
                            </span></div>
                            <div><strong style="color: #0f172a;">Địa chỉ:</strong> <span id="modalCustomerAddress">{{ $previewOrder->Address ?? 'Chưa cung cấp' }}</span></div>
                            <div><strong style="color: #0f172a;">Hình thức:</strong> <span id="modalPaymentType" class="badge-payment">{{ $previewOrder->Shippingtype ?? 'COD' }}</span></div>
                            <div><strong style="color: #0f172a;">Ngày đặt:</strong> <span id="modalOrderDate" style="color: #64748b;">{{ $previewOrder->OrderDate ?? '---' }}</span></div>

                            <div style="border-top: 1px solid #e2e8f0; margin-top: 14px; padding-top: 12px; display: flex; align-items: baseline; justify-content: space-between;">
                                <strong style="color: #0f172a; font-size: 13px;">Tổng thanh toán:</strong>
                                <span id="modalTotalPrice" style="color: var(--primary); font-weight: 800; font-size: 19px;">
                                    {{ $previewOrder ? number_format($previewOrder->Totalprice, 0, ',', '.') . ' ₫' : '0 ₫' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL FOOTER -->
        <div class="order-modal-footer">
            <!-- Form cập nhật trạng thái -->
            <form id="modalStatusForm" method="POST" action="{{ $previewOrder ? route('admin.orders.update-status', $previewOrder->OrderID) : '' }}" style="display: flex; align-items: center; gap: 8px; margin: 0; flex-wrap: wrap;">
                @csrf
                <input type="hidden" name="current_tab" value="{{ $currentStatus }}">
                <span style="font-size: 13px; font-weight: 600; color: #475569;">Cập nhật:</span>
                <select name="status" id="modalStatusSelect" class="filter-select" style="font-weight: 600; min-width: 175px;">
                    <option value="1" {{ ($previewOrder && $previewOrder->Status == '1') ? 'selected' : '' }}>1. Chờ xác nhận</option>
                    <option value="2" {{ ($previewOrder && $previewOrder->Status == '2') ? 'selected' : '' }}>2. Đang chuẩn bị</option>
                    <option value="3" {{ ($previewOrder && $previewOrder->Status == '3') ? 'selected' : '' }}>3. Đang giao hàng</option>
                    <option value="4" {{ ($previewOrder && $previewOrder->Status == '4') ? 'selected' : '' }}>4. Hoàn thành</option>
                    <option value="5" {{ ($previewOrder && $previewOrder->Status == '5') ? 'selected' : '' }}>5. Đã hủy đơn</option>
                </select>
                <button type="submit" class="btn-primary" style="padding: 7px 14px; font-size: 13px;">
                    Lưu trạng thái
                </button>
            </form>

            <div style="display: flex; align-items: center; gap: 8px;">
                <a id="modalPrintBtn" href="{{ $previewOrder ? route('admin.orders.print', $previewOrder->OrderID) : '#' }}" target="_blank" class="btn-secondary" style="padding: 7px 12px; font-size: 13px;" title="In hóa đơn">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                    <span>In hóa đơn</span>
                </a>
                <button type="button" class="btn-secondary" onclick="closeOrderModal()" style="padding: 7px 14px; font-size: 13px;">
                    Đóng
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const orderModal = document.getElementById('orderDetailModal');
    const detailUrlPattern = "{{ route('admin.orders.detail-json', ':id') }}";
    let currentRenderedId = {{ $previewOrder ? $previewOrder->OrderID : 'null' }};

    function openOrderModal(orderId) {
        if (!orderModal) return;

        // Hiện modal
        orderModal.classList.add('show');
        document.body.style.overflow = 'hidden';

        // Cập nhật preview_id trên URL mà không reload trang
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('preview_id', orderId);
        window.history.replaceState({}, '', currentUrl.toString());

        // Nếu ID trùng với order đã được SSR render sẵn thì không cần fetch lại
        if (currentRenderedId && String(currentRenderedId) === String(orderId)) {
            document.getElementById('modalLoading').style.display = 'none';
            document.getElementById('modalContent').style.display = 'grid';
            return;
        }

        // Bật loading và ẩn content
        document.getElementById('modalLoading').style.display = 'block';
        document.getElementById('modalContent').style.display = 'none';

        // Gọi API lấy thông tin chi tiết đơn
        const fetchUrl = detailUrlPattern.replace(':id', orderId);
        fetch(fetchUrl)
            .then(res => {
                if (!res.ok) throw new Error('Không thể tải dữ liệu đơn hàng');
                return res.json();
            })
            .then(data => {
                if (!data.success) {
                    alert(data.message || 'Lỗi khi tải đơn hàng');
                    closeOrderModal();
                    return;
                }

                currentRenderedId = orderId;
                const order = data.order;
                const items = data.items;

                // Header & Badges
                document.getElementById('modalOrderCode').innerText = 'Chi tiết đơn hàng ' + order.code;
                const statusBadge = document.getElementById('modalStatusBadge');
                statusBadge.className = 'status-pill ' + order.status_class;
                document.getElementById('modalStatusText').innerText = order.status_label;

                // Thông tin khách hàng & Giao nhận
                document.getElementById('modalCustomerName').innerText = order.Fulname;
                document.getElementById('modalCustomerPhone').innerHTML = order.Phonenumber && order.Phonenumber !== '---'
                    ? `<a href="tel:${order.Phonenumber}" style="color: var(--primary); text-decoration: none; font-weight: 600;">${order.Phonenumber}</a>`
                    : '---';
                document.getElementById('modalCustomerAddress').innerText = order.Address;
                document.getElementById('modalPaymentType').innerText = order.Shippingtype;
                document.getElementById('modalOrderDate').innerText = order.OrderDate;
                document.getElementById('modalTotalPrice').innerText = order.Totalprice_formatted;

                // Danh sách sản phẩm
                document.getElementById('modalItemCount').innerText = `(${items.length} món)`;
                const tbody = document.getElementById('modalItemsTableBody');
                tbody.innerHTML = '';

                if (items.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="4" style="text-align: center; padding: 24px; color: #94a3b8;">Chưa có chi tiết sản phẩm cho đơn hàng này.</td></tr>';
                } else {
                    items.forEach(item => {
                        const imgTag = item.picture_url
                            ? `<img src="${item.picture_url}" alt="${item.ProductName || ''}" style="width: 100%; height: 100%; object-fit: cover;">`
                            : `<span style="font-size: 18px;">👕</span>`;

                        const unitFormatted = new Intl.NumberFormat('vi-VN').format(item.unit_price) + ' ₫';
                        const totalFormatted = new Intl.NumberFormat('vi-VN').format(item.Price) + ' ₫';

                        const tr = `
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <div style="width: 40px; height: 40px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0;">
                                            ${imgTag}
                                        </div>
                                        <div>
                                            <div style="font-weight: 600; font-size: 13px; color: #0f172a;">${item.ProductName || ('Sản phẩm #' + item.ProductID)}</div>
                                            <div style="font-size: 11px; color: #64748b;">Mã SP: #SP-${item.ProductID}</div>
                                        </div>
                                    </div>
                                </td>
                                <td style="text-align: right; font-size: 13px; color: #334155;">${unitFormatted}</td>
                                <td style="text-align: center; font-size: 13px;"><strong>x${item.QuantityOrdered}</strong></td>
                                <td style="text-align: right; font-size: 13px; font-weight: 700; color: #0f172a;">${totalFormatted}</td>
                            </tr>
                        `;
                        tbody.insertAdjacentHTML('beforeend', tr);
                    });
                }

                // Cập nhật Form Action & In phiếu
                document.getElementById('modalStatusForm').action = data.update_url;
                document.getElementById('modalStatusSelect').value = order.Status;
                document.getElementById('modalPrintBtn').href = data.print_url;

                // Tắt spinner và hiển thị nội dung
                document.getElementById('modalLoading').style.display = 'none';
                document.getElementById('modalContent').style.display = 'grid';
            })
            .catch(err => {
                console.error(err);
                alert('Không thể kết nối đến máy chủ để tải đơn hàng.');
                closeOrderModal();
            });
    }

    function closeOrderModal() {
        if (!orderModal) return;
        orderModal.classList.remove('show');
        document.body.style.overflow = '';

        // Xóa preview_id khỏi query URL mà không tải lại trang
        const currentUrl = new URL(window.location.href);
        if (currentUrl.searchParams.has('preview_id')) {
            currentUrl.searchParams.delete('preview_id');
            const cleanQuery = currentUrl.searchParams.toString() ? '?' + currentUrl.searchParams.toString() : '';
            window.history.replaceState({}, '', currentUrl.pathname + cleanQuery);
        }
    }

    function handleOverlayClick(event) {
        if (event.target === orderModal) {
            closeOrderModal();
        }
    }

    // Đóng khi bấm phím ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && orderModal && orderModal.classList.contains('show')) {
            closeOrderModal();
        }
    });

    // Tự động mở modal nếu có tham số preview_id trên URL (khi vào link trực tiếp hoặc vừa cập nhật xong)
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const previewId = urlParams.get('preview_id');
        if (previewId) {
            openOrderModal(previewId);
        }
    });
</script>
@endsection
