@extends('admin.layout')

@section('styles')
<style>
    .tabs-header {
        display: flex;
        gap: 8px;
        border-bottom: 1px solid var(--border-color);
        margin-bottom: 24px;
        padding-bottom: 0;
    }

    .tab-btn {
        background: none;
        border: none;
        padding: 12px 18px;
        font-size: 14px;
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

    .thumb-box {
        width: 44px;
        height: 44px;
        border-radius: 6px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .thumb-box img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .manage-grid {
        display: grid;
        grid-template-columns: 2fr 1.2fr;
        gap: 24px;
    }

    .form-group {
        margin-bottom: 14px;
    }

    .form-label {
        display: block;
        font-size: 12px;
        font-weight: 700;
        color: #475569;
        margin-bottom: 6px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .form-input {
        width: 100%;
        padding: 9px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        font-size: 13px;
        outline: none;
        background: #f8fafc;
        color: var(--text-main);
    }

    .form-input:focus {
        border-color: var(--primary);
        background: #ffffff;
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Quản lý Danh mục & Thương hiệu</h1>
        <p class="page-subtitle">Phân loại sản phẩm và quản lý các nhãn hiệu thời trang kinh doanh trên hệ thống</p>
    </div>
</div>

<div class="card">
    <!-- TABS -->
    <div class="tabs-header">
        <a href="{{ route('admin.categories.index', ['tab' => 'categories']) }}" 
           class="tab-btn {{ $activeTab === 'categories' ? 'active' : '' }}">
            <span>📁 Danh mục sản phẩm</span>
            <span class="tab-count">{{ $categories->count() }}</span>
        </a>
        <a href="{{ route('admin.categories.index', ['tab' => 'brands']) }}" 
           class="tab-btn {{ $activeTab === 'brands' ? 'active' : '' }}">
            <span>🏷️ Thương hiệu (Brands)</span>
            <span class="tab-count">{{ $brands->count() }}</span>
        </a>
    </div>

    @if ($errors->any())
        <div style="background: #fee2e2; border: 1px solid #fecaca; color: #991b1b; padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; font-size: 13px;">
            <ul style="margin: 0; padding-left: 18px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($activeTab === 'categories')
        <!-- TAB DANH MỤC -->
        <div class="manage-grid">
            <!-- BẢNG DANH MỤC -->
            <div>
                <h3 style="font-size: 15px; font-weight: 700; color: #0f172a; margin-bottom: 12px;">
                    Danh sách danh mục hiện có ({{ $categories->count() }})
                </h3>
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Ảnh</th>
                                <th>Tên danh mục</th>
                                <th>Sản phẩm</th>
                                <th style="text-align: right;">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $cat)
                                <tr>
                                    <td>
                                        <div class="thumb-box">
                                            @if($cat->Picture && file_exists(public_path('assets/admin/img/upload/' . $cat->Picture)))
                                                <img src="{{ asset('assets/admin/img/upload/' . $cat->Picture) }}" alt="{{ $cat->CategoryName }}">
                                            @else
                                                <span style="font-size: 18px;">📁</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <strong style="color: #0f172a; font-size: 14px;">{{ $cat->CategoryName }}</strong>
                                        @if($cat->Description)
                                            <div style="font-size: 12px; color: #64748b; max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                {{ strip_tags($cat->Description) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="status-pill status-completed">
                                            <span class="dot"></span>{{ $cat->total_products }} sản phẩm
                                        </span>
                                    </td>
                                    <td style="text-align: right;">
                                        <div style="display: inline-flex; gap: 6px;">
                                            <button class="btn-icon-sm" title="Chỉnh sửa" onclick="editCategory({{ $cat->CategoryID }}, '{{ addslashes($cat->CategoryName) }}', '{{ addslashes(strip_tags($cat->Description)) }}')">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                            </button>

                                            <form action="{{ route('admin.categories.destroy', $cat->CategoryID) }}" method="POST" style="margin: 0;"
                                                  onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục \"{{ addslashes($cat->CategoryName) }}\"?');">
                                                @csrf
                                                <button type="submit" class="btn-icon-sm delete" title="Xóa danh mục">
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" style="text-align: center; padding: 24px; color: #94a3b8;">Chưa có danh mục nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- FORM THÊM / SỬA DANH MỤC -->
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px;">
                <h3 id="cat-form-title" style="font-size: 15px; font-weight: 700; color: #0f172a; margin-bottom: 16px;">
                    ✨ Thêm mới Danh mục
                </h3>

                <form id="cat-form" action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label class="form-label" for="cat_name">Tên danh mục *</label>
                        <input type="text" name="CategoryName" id="cat_name" class="form-input" placeholder="Ví dụ: Áo khoác, Đầm dạ hội..." required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="cat_pic">Ảnh đại diện danh mục</label>
                        <input type="file" name="Picture" id="cat_pic" class="form-input" accept="image/*">
                        <small style="color: #64748b; font-size: 11px;">Tải lên ảnh vuông JPG, PNG tối đa 2MB</small>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="cat_desc">Mô tả ngắn</label>
                        <textarea name="Description" id="cat_desc" class="form-input" rows="3" placeholder="Mô tả nhóm sản phẩm..."></textarea>
                    </div>

                    <div style="display: flex; gap: 8px; margin-top: 18px;">
                        <button type="button" id="cat-btn-cancel" class="btn-secondary" style="display: none;" onclick="resetCategoryForm()">Hủy</button>
                        <button type="submit" id="cat-btn-submit" class="btn-primary" style="flex: 1; justify-content: center;">
                            Lưu danh mục
                        </button>
                    </div>
                </form>
            </div>
        </div>

    @else
        <!-- TAB THƯƠNG HIỆU -->
        <div class="manage-grid">
            <!-- BẢNG THƯƠNG HIỆU -->
            <div>
                <h3 style="font-size: 15px; font-weight: 700; color: #0f172a; margin-bottom: 12px;">
                    Danh sách thương hiệu đối tác ({{ $brands->count() }})
                </h3>
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Logo</th>
                                <th>Tên thương hiệu</th>
                                <th>Sản phẩm</th>
                                <th style="text-align: right;">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($brands as $br)
                                <tr>
                                    <td>
                                        <div class="thumb-box">
                                            @if($br->Logo && file_exists(public_path('assets/admin/img/upload/' . $br->Logo)))
                                                <img src="{{ asset('assets/admin/img/upload/' . $br->Logo) }}" alt="{{ $br->BrandName }}">
                                            @else
                                                <span style="font-size: 18px;">🏷️</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <strong style="color: #0f172a; font-size: 14px;">{{ $br->BrandName }}</strong>
                                        @if($br->BrandDesc)
                                            <div style="font-size: 12px; color: #64748b; max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                {{ strip_tags($br->BrandDesc) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="status-pill status-completed">
                                            <span class="dot"></span>{{ $br->total_products }} sản phẩm
                                        </span>
                                    </td>
                                    <td style="text-align: right;">
                                        <div style="display: inline-flex; gap: 6px;">
                                            <button class="btn-icon-sm" title="Chỉnh sửa" onclick="editBrand({{ $br->BrandID }}, '{{ addslashes($br->BrandName) }}', '{{ addslashes(strip_tags($br->BrandDesc)) }}')">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                            </button>

                                            <form action="{{ route('admin.brands.destroy', $br->BrandID) }}" method="POST" style="margin: 0;"
                                                  onsubmit="return confirm('Bạn có chắc chắn muốn xóa thương hiệu \"{{ addslashes($br->BrandName) }}\"?');">
                                                @csrf
                                                <button type="submit" class="btn-icon-sm delete" title="Xóa thương hiệu">
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" style="text-align: center; padding: 24px; color: #94a3b8;">Chưa có thương hiệu nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- FORM THÊM / SỬA THƯƠNG HIỆU -->
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px;">
                <h3 id="brand-form-title" style="font-size: 15px; font-weight: 700; color: #0f172a; margin-bottom: 16px;">
                    ✨ Thêm mới Thương hiệu
                </h3>

                <form id="brand-form" action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label class="form-label" for="brand_name">Tên thương hiệu *</label>
                        <input type="text" name="BrandName" id="brand_name" class="form-input" placeholder="Ví dụ: Zara, H&M, Uniqlo..." required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="brand_logo">Logo thương hiệu *</label>
                        <input type="file" name="Logo" id="brand_logo" class="form-input" accept="image/*" required>
                        <small style="color: #64748b; font-size: 11px;">Tải lên logo PNG trong suốt hoặc JPG</small>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="brand_desc">Mô tả thương hiệu</label>
                        <textarea name="BrandDesc" id="brand_desc" class="form-input" rows="3" placeholder="Thông tin xuất xứ, phong cách..."></textarea>
                    </div>

                    <div style="display: flex; gap: 8px; margin-top: 18px;">
                        <button type="button" id="brand-btn-cancel" class="btn-secondary" style="display: none;" onclick="resetBrandForm()">Hủy</button>
                        <button type="submit" id="brand-btn-submit" class="btn-primary" style="flex: 1; justify-content: center;">
                            Lưu thương hiệu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    function editCategory(id, name, desc) {
        document.getElementById('cat-form-title').innerText = '✏️ Cập nhật Danh mục: ' + name;
        document.getElementById('cat-form').action = '/admin/categories/update/' + id;
        document.getElementById('cat_name').value = name;
        document.getElementById('cat_desc').value = desc;
        document.getElementById('cat-btn-cancel').style.display = 'inline-block';
        document.getElementById('cat-btn-submit').innerText = 'Cập nhật danh mục';
    }

    function resetCategoryForm() {
        document.getElementById('cat-form-title').innerText = '✨ Thêm mới Danh mục';
        document.getElementById('cat-form').action = '{{ route("admin.categories.store") }}';
        document.getElementById('cat_name').value = '';
        document.getElementById('cat_desc').value = '';
        document.getElementById('cat-btn-cancel').style.display = 'none';
        document.getElementById('cat-btn-submit').innerText = 'Lưu danh mục';
    }

    function editBrand(id, name, desc) {
        document.getElementById('brand-form-title').innerText = '✏️ Cập nhật Thương hiệu: ' + name;
        document.getElementById('brand-form').action = '/admin/brands/update/' + id;
        document.getElementById('brand_name').value = name;
        document.getElementById('brand_desc').value = desc;
        document.getElementById('brand_logo').required = false;
        document.getElementById('brand-btn-cancel').style.display = 'inline-block';
        document.getElementById('brand-btn-submit').innerText = 'Cập nhật thương hiệu';
    }

    function resetBrandForm() {
        document.getElementById('brand-form-title').innerText = '✨ Thêm mới Thương hiệu';
        document.getElementById('brand-form').action = '{{ route("admin.brands.store") }}';
        document.getElementById('brand_name').value = '';
        document.getElementById('brand_desc').value = '';
        document.getElementById('brand_logo').required = true;
        document.getElementById('brand-btn-cancel').style.display = 'none';
        document.getElementById('brand-btn-submit').innerText = 'Lưu thương hiệu';
    }
</script>
@endsection
