@extends('admin.layout')

@section('styles')
<style>
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
        <h1 class="page-title">Quản lý Khách hàng</h1>
        <p class="page-subtitle">
            Tổng cộng <strong>{{ $totalCustomers }}</strong> tài khoản người dùng
            • <span style="color:#10b981; font-weight:600;">{{ $activeCustomers }} đang hoạt động</span>
            @if($lockedCustomers > 0)
                • <span style="color:#ef4444; font-weight:600;">{{ $lockedCustomers }} bị khóa</span>
            @endif
        </p>
    </div>
</div>

<div class="card">
    <!-- TOOLBAR -->
    <form method="GET" action="{{ route('admin.customers.index') }}" class="filter-toolbar">
        <div class="filter-left">
            <div style="position: relative; width: 280px;">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Tên khách, username, SĐT, email..." class="filter-select" style="width: 100%; padding-left: 32px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%);">
                    <circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </div>

            <select name="status" class="filter-select" onchange="this.form.submit()">
                <option value="all">Trạng thái: Tất cả</option>
                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Đang hoạt động</option>
                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Đã khóa</option>
            </select>

            <button type="submit" class="btn-secondary" style="padding: 7px 12px;">Lọc</button>
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.customers.index') }}" class="btn-secondary" style="color: #64748b;">Xóa lọc</a>
            @endif
        </div>

        <div style="font-size: 13px; color: var(--text-muted);">
            Hiển thị <strong>{{ $customers->count() }}</strong> / <strong>{{ $customers->total() }}</strong> khách hàng
        </div>
    </form>

    <!-- BẢNG KHÁCH HÀNG -->
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Mã KH</th>
                    <th>Khách hàng & Tài khoản</th>
                    <th>Giới tính</th>
                    <th>Liên hệ (SĐT / Email)</th>
                    <th>Địa chỉ nhận hàng</th>
                    <th>Đơn đã đặt</th>
                    <th>Tổng chi tiêu</th>
                    <th>Trạng thái</th>
                    <th style="text-align: right;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $cus)
                    <tr>
                        <td>
                            <strong style="color: #0f172a; font-size: 13px;">#KH-{{ str_pad($cus->CustomerID, 4, '0', STR_PAD_LEFT) }}</strong>
                        </td>
                        <td>
                            <div style="font-weight: 600; color: #0f172a; font-size: 14px;">{{ $cus->Fullname ?? 'Chưa đặt tên' }}</div>
                            <div style="font-size: 12px; color: #64748b;">@ {{ $cus->Username ?? 'no-username' }}</div>
                        </td>
                        <td>
                            @if(strtolower($cus->Gender) === 'male' || $cus->Gender == '1' || strtolower($cus->Gender) === 'nam')
                                <span style="font-size: 12px; color: #2563eb;">Nam</span>
                            @elseif(strtolower($cus->Gender) === 'female' || $cus->Gender == '0' || strtolower($cus->Gender) === 'nữ')
                                <span style="font-size: 12px; color: #ec4899;">Nữ</span>
                            @else
                                <span style="font-size: 12px; color: #64748b;">---</span>
                            @endif
                        </td>
                        <td>
                            <div style="font-size: 13px; color: #0f172a; font-weight: 500;">{{ $cus->Phonenumber ?? 'Chưa có SĐT' }}</div>
                            <div style="font-size: 11px; color: #64748b;">{{ $cus->Email ?? 'Chưa có email' }}</div>
                        </td>
                        <td>
                            <div style="font-size: 13px; max-width: 220px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $cus->Address }}">
                                {{ $cus->Address ?? 'Chưa cập nhật' }}
                            </div>
                        </td>
                        <td>
                            <strong style="color: #0f172a;">{{ $cus->total_orders }}</strong> đơn
                        </td>
                        <td>
                            <strong style="color: #10b981; font-size: 13px;">{{ number_format($cus->total_spent, 0, ',', '.') }} ₫</strong>
                        </td>
                        <td>
                            @if($cus->Status == 1)
                                <span class="status-pill status-completed">
                                    <span class="dot"></span>Hoạt động
                                </span>
                            @else
                                <span class="status-pill status-cancelled">
                                    <span class="dot"></span>Đã khóa
                                </span>
                            @endif
                        </td>
                        <td style="text-align: right;">
                            <form action="{{ route('admin.customers.toggle-status', $cus->CustomerID) }}" method="POST" style="margin: 0;"
                                  onsubmit="return confirm('Bạn có chắc muốn {{ $cus->Status == 1 ? 'khóa' : 'mở khóa' }} tài khoản {{ addslashes($cus->Fullname) }}?');">
                                @csrf
                                @if($cus->Status == 1)
                                    <button type="submit" class="btn-secondary" style="padding: 5px 10px; font-size: 12px; color: #b91c1c; border-color: #fecaca;" title="Khóa tài khoản này">
                                        Khóa tài khoản
                                    </button>
                                @else
                                    <button type="submit" class="btn-primary" style="padding: 5px 10px; font-size: 12px;" title="Mở khóa tài khoản">
                                        Mở khóa
                                    </button>
                                @endif
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align: center; padding: 36px; color: #94a3b8;">
                            Không tìm thấy tài khoản khách hàng nào.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- PHÂN TRANG -->
    @if($customers->hasPages())
        <div style="display: flex; justify-content: flex-end; margin-top: 20px;">
            {{ $customers->links() }}
        </div>
    @endif
</div>
@endsection
