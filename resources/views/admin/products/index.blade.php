@extends('admin.layout')

@section('styles')
<style>
    .product-thumbnail {
        width: 48px;
        height: 48px;
        border-radius: 6px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .product-thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: cover;
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
        <h1 class="page-title">Quản lý kho sản phẩm</h1>
        <p class="page-subtitle">
            Tổng cộng <strong>{{ $totalCount }}</strong> mặt hàng
            @if($lowStockCount > 0) • <span style="color:#d97706; font-weight:600;">{{ $lowStockCount }} sắp hết hàng</span>@endif
            @if($outOfStockCount > 0) • <span style="color:#ef4444; font-weight:600;">{{ $outOfStockCount }} hết hàng</span>@endif
        </p>
    </div>
    <div>
        <a href="{{ route('admin.products.create') }}" class="btn-primary" style="box-shadow: 0 2px 6px rgba(113, 205, 20, 0.25);">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            <span>Thêm sản phẩm mới</span>
        </a>
    </div>
</div>

<div class="card">
    <!-- BỘ LỌC TÌM KIẾM SẢN PHẨM -->
    <form method="GET" action="{{ route('admin.products.index') }}" class="filter-toolbar">
        <div class="filter-left">
            <div style="position: relative; width: 240px;">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Tên sản phẩm, mã #SP..." class="filter-select" style="width: 100%; padding-left: 32px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%);">
                    <circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </div>

            <select name="category_id" class="filter-select" onchange="this.form.submit()">
                <option value="all">Danh mục: Tất cả</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->CategoryID }}" {{ request('category_id') == $cat->CategoryID ? 'selected' : '' }}>
                        {{ $cat->CategoryName }}
                    </option>
                @endforeach
            </select>

            <select name="brand_id" class="filter-select" onchange="this.form.submit()">
                <option value="all">Thương hiệu: Tất cả</option>
                @foreach($brands as $br)
                    <option value="{{ $br->BrandID }}" {{ request('brand_id') == $br->BrandID ? 'selected' : '' }}>
                        {{ $br->BrandName }}
                    </option>
                @endforeach
            </select>

            <select name="stock" class="filter-select" onchange="this.form.submit()">
                <option value="all">Tồn kho: Tất cả</option>
                <option value="in_stock" {{ request('stock') == 'in_stock' ? 'selected' : '' }}>Còn hàng (&gt; 5)</option>
                <option value="low_stock" {{ request('stock') == 'low_stock' ? 'selected' : '' }}>Sắp hết (1 - 5)</option>
                <option value="out_of_stock" {{ request('stock') == 'out_of_stock' ? 'selected' : '' }}>Hết hàng (0)</option>
            </select>

            <button type="submit" class="btn-secondary" style="padding: 7px 12px;">Lọc</button>
            @if(request()->hasAny(['search', 'category_id', 'brand_id', 'stock']))
                <a href="{{ route('admin.products.index') }}" class="btn-secondary" style="color: #64748b;">Xóa lọc</a>
            @endif
        </div>

        <div style="font-size: 13px; color: var(--text-muted);">
            Hiển thị <strong>{{ $products->count() }}</strong> / <strong>{{ $products->total() }}</strong> sản phẩm
        </div>
    </form>

    <!-- BẢNG DANH SÁCH SẢN PHẨM -->
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Ảnh SP</th>
                    <th>Tên sản phẩm</th>
                    <th>Danh mục</th>
                    <th>Thương hiệu</th>
                    <th>Nhóm</th>
                    <th>Giá bán</th>
                    <th>Tồn kho</th>
                    <th>Đánh giá</th>
                    <th style="text-align: right;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $prod)
                    <tr>
                        <td>
                            <div class="product-thumbnail">
                                @if($prod->Picture && file_exists(public_path('assets/admin/img/upload/' . $prod->Picture)))
                                    <img src="{{ asset('assets/admin/img/upload/' . $prod->Picture) }}" alt="{{ $prod->ProductName }}">
                                @else
                                    <span style="font-size: 20px;">👕</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 600; color: #0f172a; font-size: 14px;">{{ $prod->ProductName }}</div>
                            <div style="font-size: 11px; color: #64748b;">
                                Mã SP: #SP-{{ str_pad($prod->ProductID, 4, '0', STR_PAD_LEFT) }}
                                @if($prod->UpdateDate) • Cập nhật: {{ $prod->UpdateDate }}@endif
                            </div>
                        </td>
                        <td><span style="font-size: 13px; color: #334155;">{{ $prod->CategoryName ?? '---' }}</span></td>
                        <td><span style="font-size: 13px; color: #334155;">{{ $prod->BrandName ?? '---' }}</span></td>
                        <td>
                            @if($prod->ProductStatusID == 1)
                                <span style="font-size: 11px; background: #e0f2fe; color: #0369a1; padding: 2px 8px; border-radius: 4px; font-weight: 600; white-space: nowrap;">Mới</span>
                            @elseif($prod->ProductStatusID == 2)
                                <span style="font-size: 11px; background: #fef3c7; color: #92400e; padding: 2px 8px; border-radius: 4px; font-weight: 600; white-space: nowrap;">Nổi bật</span>
                            @else
                                <span style="font-size: 11px; background: #fee2e2; color: #991b1b; padding: 2px 8px; border-radius: 4px; font-weight: 600; white-space: nowrap;">Giảm giá</span>
                            @endif
                        </td>
                        <td style="white-space: nowrap;"><strong style="color: #0f172a; font-size: 14px;">{{ number_format($prod->Price, 0, ',', '.') }} ₫</strong></td>
                        <td>
                            @if($prod->Quantity > 5)
                                <span class="status-pill status-completed"><span class="dot"></span>Còn {{ $prod->Quantity }} cái</span>
                            @elseif($prod->Quantity > 0)
                                <span class="status-pill status-pending"><span class="dot"></span>Sắp hết ({{ $prod->Quantity }})</span>
                            @else
                                <span class="status-pill status-cancelled"><span class="dot"></span>Hết hàng (0)</span>
                            @endif
                        </td>
                        <td>
                            @if($prod->QuantityRating > 0)
                                <span style="color: #f59e0b; font-weight: 600;">⭐</span>
                                <span style="font-size: 12px; color: #475569;">({{ $prod->QuantityRating }})</span>
                            @else
                                <span style="font-size: 12px; color: #94a3b8;">Chưa có</span>
                            @endif
                        </td>
                        <td style="text-align: right;">
                            <div style="display: inline-flex; gap: 6px;">
                                <a href="{{ route('admin.products.edit', $prod->ProductID) }}" class="btn-icon-sm" title="Chỉnh sửa sản phẩm">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </a>

                                <form action="{{ route('admin.products.destroy', $prod->ProductID) }}" method="POST" style="margin: 0;" 
                                      onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm \"{{ addslashes($prod->ProductName) }}\" không?');">
                                    @csrf
                                    <button type="submit" class="btn-icon-sm delete" title="Xóa sản phẩm">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align: center; padding: 36px; color: #94a3b8;">
                            Không tìm thấy sản phẩm nào phù hợp.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- PHÂN TRANG -->
    @if($products->hasPages())
        <div style="display: flex; justify-content: flex-end; margin-top: 20px;">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection
