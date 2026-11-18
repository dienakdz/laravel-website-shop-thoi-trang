@extends('admin.layout')

@section('styles')
<style>
    /* LIVE PREVIEW BANNER HERO */
    .hero-preview-container {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 28px;
        background: #0f172a;
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.15);
        border: 1px solid #e2e8f0;
    }

    .hero-preview-bg {
        width: 100%;
        height: 320px;
        background-size: cover;
        background-position: center;
        position: relative;
        display: flex;
        align-items: center;
        padding: 40px 60px;
    }

    .hero-preview-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.8) 45%, rgba(255, 255, 255, 0.1) 100%);
    }

    .hero-preview-content {
        position: relative;
        z-index: 2;
        max-width: 580px;
    }

    .hero-sub {
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: #64748b;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .hero-title {
        font-size: 32px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.25;
        margin-bottom: 12px;
    }

    .hero-desc {
        font-size: 14px;
        color: #475569;
        line-height: 1.6;
        margin-bottom: 20px;
    }

    .hero-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: var(--primary);
        color: #ffffff;
        font-size: 13px;
        font-weight: 700;
        padding: 10px 22px;
        border-radius: 30px;
        text-decoration: none;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 12px rgba(113, 205, 20, 0.35);
    }

    .live-badge {
        position: absolute;
        top: 16px;
        right: 16px;
        z-index: 3;
        background: rgba(15, 23, 42, 0.85);
        backdrop-filter: blur(4px);
        color: #ffffff;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
        border: 1px solid rgba(255, 255, 255, 0.15);
    }

    .live-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #22c55e;
        box-shadow: 0 0 8px #22c55e;
        animation: pulse 1.5s infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(1.2); }
    }

    /* MODAL POPUP */
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
    }

    .modal-overlay.show {
        display: flex;
    }

    .modal-dialog-custom {
        background: #ffffff;
        border-radius: 12px;
        max-width: 650px;
        width: 100%;
        max-height: 90vh;
        display: flex;
        flex-direction: column;
        box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25);
        overflow: hidden;
    }

    .modal-header-custom {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 18px 24px;
        border-bottom: 1px solid #e2e8f0;
    }

    .modal-body-custom {
        padding: 24px;
        overflow-y: auto;
    }

    .modal-footer-custom {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        padding: 14px 24px;
        border-top: 1px solid #e2e8f0;
        background: #f8fafc;
    }

    .upload-box {
        border: 2px dashed #cbd5e1;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        background: #f8fafc;
        cursor: pointer;
        transition: all 0.2s;
    }

    .upload-box:hover {
        border-color: var(--primary);
        background: #f0fdf4;
    }

    .preview-img-container {
        margin-top: 12px;
        border-radius: 6px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        max-height: 180px;
        display: none;
    }

    .preview-img-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>
@endsection

@section('content')
<div class="page-header" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
    <div>
        <h1 class="page-title">Quản lý Banner Trang chủ</h1>
        <p class="page-subtitle">Cập nhật hình ảnh, thông điệp chiến dịch và chương trình khuyến mãi hiển thị trên trang chủ</p>
    </div>
    <div style="display: flex; gap: 10px;">
        <button type="button" class="btn-primary" onclick="openCreateModal()">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            <span>Thêm banner mới</span>
        </button>
        <a href="{{ route('home') }}" target="_blank" class="btn-secondary" title="Xem trang chủ thực tế">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
            <span>Xem trang chủ</span>
        </a>
    </div>
</div>

<!-- KHUNG LIVE PREVIEW HERO BANNER -->
@if($activeBanner)
    <div class="hero-preview-container">
        <div class="live-badge">
            <span class="live-dot"></span>
            <span>Đang hiển thị trên trang chủ</span>
        </div>

        <div class="hero-preview-bg" style="background-image: url('{{ $activeBanner->image_url }}')">
            <div class="hero-preview-overlay"></div>
            <div class="hero-preview-content">
                <div class="hero-sub">Thời trang xu hướng mới</div>
                <div class="hero-title">{!! $activeBanner->name_banner !!}</div>
                <div class="hero-desc">
                    {{ !empty($activeBanner->content_banner) ? $activeBanner->content_banner : 'Tự tin thể hiện phong cách riêng với những mẫu thiết kế độc đáo.' }}
                </div>
                <div>
                    <span class="hero-btn">
                        Khám phá ngay
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </span>
                    <button type="button" class="btn-secondary" style="margin-left: 10px; background: rgba(255,255,255,0.9); font-size: 13px; padding: 9px 18px; border-radius: 30px;"
                            onclick="openEditModal({{ $activeBanner->id }}, '{{ addslashes($activeBanner->name_banner) }}', '{{ addslashes($activeBanner->content_banner) }}', '{{ $activeBanner->image_url }}')">
                        ✏️ Sửa banner này
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- BẢNG DANH SÁCH TẤT CẢ BANNER -->
<div class="card">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
        <h2 style="font-size: 16px; font-weight: 700; color: #0f172a; margin: 0;">
            Danh sách Banner chiến dịch ({{ $mappedBanners->count() }} banner)
        </h2>
        <span style="font-size: 12px; color: var(--text-muted);">
            Kích thước tối ưu: <strong>1920 x 700 px</strong> hoặc <strong>1600 x 600 px</strong>
        </span>
    </div>

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 220px;">Ảnh Banner</th>
                    <th>Tiêu đề chính</th>
                    <th>Nội dung / Mô tả</th>
                    <th>Tên file ảnh</th>
                    <th>Trạng thái</th>
                    <th style="text-align: right;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mappedBanners as $item)
                    <tr>
                        <td>
                            <div style="width: 200px; height: 75px; border-radius: 6px; overflow: hidden; border: 1px solid #e2e8f0; background: #f8fafc; position: relative;">
                                <img src="{{ $item->image_url }}" alt="Banner" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 700; font-size: 14px; color: #0f172a;">
                                {!! $item->name_banner !!}
                            </div>
                        </td>
                        <td>
                            <div style="font-size: 13px; color: #475569; max-width: 280px; line-height: 1.5;">
                                {{ !empty($item->content_banner) ? $item->content_banner : 'Mặc định (Tự tin thể hiện phong cách riêng...)' }}
                            </div>
                        </td>
                        <td style="font-size: 12px; color: #64748b; font-family: monospace;">
                            {{ $item->image_banner }}
                        </td>
                        <td>
                            @if($item->is_active)
                                <span class="status-pill status-completed">
                                    <span class="dot"></span>Đang hiển thị
                                </span>
                            @else
                                <span class="status-pill" style="background: #f1f5f9; color: #64748b;">
                                    <span class="dot" style="background: #94a3b8;"></span>Sẵn sàng
                                </span>
                            @endif
                        </td>
                        <td style="text-align: right;">
                            <div style="display: inline-flex; align-items: center; gap: 6px;">
                                @if(!$item->is_active)
                                    <form action="{{ route('admin.banners.set-active', $item->id) }}" method="POST" style="margin: 0;">
                                        @csrf
                                        <button type="submit" class="btn-primary" style="padding: 5px 10px; font-size: 11px;" title="Đặt làm banner chính trên trang chủ">
                                            Kích hoạt
                                        </button>
                                    </form>
                                @endif

                                <button type="button" class="btn-icon-sm" title="Chỉnh sửa banner"
                                        onclick="openEditModal({{ $item->id }}, '{{ addslashes($item->name_banner) }}', '{{ addslashes($item->content_banner) }}', '{{ $item->image_url }}')">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </button>

                                @if($mappedBanners->count() > 1)
                                    <form action="{{ route('admin.banners.destroy', $item->id) }}" method="POST" style="margin: 0;"
                                          onsubmit="return confirm('Bạn có chắc chắn muốn xóa banner này khỏi trang chủ?');">
                                        @csrf
                                        <button type="submit" class="btn-icon-sm" style="color: #ef4444;" title="Xóa banner">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 36px; color: #94a3b8;">
                            Chưa có banner nào trong hệ thống.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL THÊM BANNER MỚI -->
<div class="modal-overlay" id="createBannerModal" onclick="if(event.target===this) closeCreateModal();">
    <div class="modal-dialog-custom">
        <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-header-custom">
                <h3 style="font-size: 16px; font-weight: 700; color: #0f172a; margin: 0;">➕ Thêm Banner Chiến dịch Mới</h3>
                <span style="font-size: 22px; cursor: pointer; color: #94a3b8;" onclick="closeCreateModal()">&times;</span>
            </div>

            <div class="modal-body-custom">
                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="font-weight: 600; font-size: 13px; display: block; margin-bottom: 6px;">
                        Tiêu đề Banner <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" name="name_banner" class="form-control" required
                           placeholder="Ví dụ: BST Thu Đông 2026 <br> Phong cách tối giản" style="width: 100%;">
                    <small style="color: #64748b; font-size: 11px;">Mẹo: Bạn có thể nhập thẻ <code>&lt;br&gt;</code> để chủ động xuống dòng tiêu đề đẹp hơn.</small>
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="font-weight: 600; font-size: 13px; display: block; margin-bottom: 6px;">
                        Mô tả ngắn / Slogan khuyến mãi
                    </label>
                    <textarea name="content_banner" class="form-control" rows="2"
                              placeholder="Ví dụ: Giảm giá đến 50% cho toàn bộ sản phẩm thiết kế mới..." style="width: 100%;"></textarea>
                </div>

                <div class="form-group" style="margin-bottom: 12px;">
                    <label class="form-label" style="font-weight: 600; font-size: 13px; display: block; margin-bottom: 6px;">
                        Tải lên hình ảnh Banner <span style="color: #ef4444;">*</span>
                    </label>
                    <div class="upload-box" onclick="document.getElementById('createImageInput').click()">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" style="margin: 0 auto 8px;"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                        <div style="font-size: 13px; font-weight: 600; color: #334155;">Nhấn để chọn ảnh từ máy tính</div>
                        <div style="font-size: 11px; color: #94a3b8; margin-top: 4px;">Hỗ trợ: JPG, PNG, WEBP (Tối đa 5MB. Kích thước chuẩn: 1920x700px)</div>
                        <input type="file" id="createImageInput" name="image_banner" accept="image/*" required style="display: none;" onchange="previewImage(this, 'createImgPreviewBox', 'createImgPreview')">
                    </div>

                    <div class="preview-img-container" id="createImgPreviewBox">
                        <img id="createImgPreview" src="" alt="Preview">
                    </div>
                </div>
            </div>

            <div class="modal-footer-custom">
                <button type="button" class="btn-secondary" onclick="closeCreateModal()">Hủy</button>
                <button type="submit" class="btn-primary" style="padding: 8px 18px;">Thêm banner</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL CHỈNH SỬA BANNER -->
<div class="modal-overlay" id="editBannerModal" onclick="if(event.target===this) closeEditModal();">
    <div class="modal-dialog-custom">
        <form id="editBannerForm" method="POST" action="" enctype="multipart/form-data">
            @csrf
            <div class="modal-header-custom">
                <h3 style="font-size: 16px; font-weight: 700; color: #0f172a; margin: 0;">✏️ Chỉnh sửa Banner Trang chủ</h3>
                <span style="font-size: 22px; cursor: pointer; color: #94a3b8;" onclick="closeEditModal()">&times;</span>
            </div>

            <div class="modal-body-custom">
                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="font-weight: 600; font-size: 13px; display: block; margin-bottom: 6px;">
                        Tiêu đề Banner <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" id="edit_name_banner" name="name_banner" class="form-control" required style="width: 100%;">
                    <small style="color: #64748b; font-size: 11px;">Mẹo: Bạn có thể nhập thẻ <code>&lt;br&gt;</code> để chủ động xuống dòng tiêu đề đẹp hơn.</small>
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="font-weight: 600; font-size: 13px; display: block; margin-bottom: 6px;">
                        Mô tả ngắn / Slogan khuyến mãi
                    </label>
                    <textarea id="edit_content_banner" name="content_banner" class="form-control" rows="2" style="width: 100%;"></textarea>
                </div>

                <div class="form-group" style="margin-bottom: 12px;">
                    <label class="form-label" style="font-weight: 600; font-size: 13px; display: block; margin-bottom: 6px;">
                        Hình ảnh Banner (Để trống nếu giữ nguyên ảnh hiện tại)
                    </label>
                    <div class="upload-box" onclick="document.getElementById('editImageInput').click()">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" style="margin: 0 auto 8px;"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                        <div style="font-size: 13px; font-weight: 600; color: #334155;">Nhấn để tải lên ảnh mới</div>
                        <div style="font-size: 11px; color: #94a3b8; margin-top: 4px;">Kích thước tối ưu: 1920x700px</div>
                        <input type="file" id="editImageInput" name="image_banner" accept="image/*" style="display: none;" onchange="previewImage(this, 'editImgPreviewBox', 'editImgPreview')">
                    </div>

                    <div class="preview-img-container" id="editImgPreviewBox" style="display: block;">
                        <img id="editImgPreview" src="" alt="Current Image">
                    </div>
                </div>
            </div>

            <div class="modal-footer-custom">
                <button type="button" class="btn-secondary" onclick="closeEditModal()">Hủy</button>
                <button type="submit" class="btn-primary" style="padding: 8px 18px;">Lưu thay đổi</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const updateUrlPattern = "{{ route('admin.banners.update', ':id') }}";

    function openCreateModal() {
        document.getElementById('createBannerModal').classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeCreateModal() {
        document.getElementById('createBannerModal').classList.remove('show');
        document.body.style.overflow = '';
    }

    function openEditModal(id, name, content, imageUrl) {
        document.getElementById('edit_name_banner').value = name;
        document.getElementById('edit_content_banner').value = content;
        document.getElementById('editImgPreview').src = imageUrl;
        document.getElementById('editImgPreviewBox').style.display = 'block';
        document.getElementById('editBannerForm').action = updateUrlPattern.replace(':id', id);

        document.getElementById('editBannerModal').classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeEditModal() {
        document.getElementById('editBannerModal').classList.remove('show');
        document.body.style.overflow = '';
    }

    function previewImage(input, boxId, imgId) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById(imgId).src = e.target.result;
                document.getElementById(boxId).style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Đóng modal khi nhấn ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeCreateModal();
            closeEditModal();
        }
    });

    // Tự động mở modal nếu có query param (hỗ trợ test & preview)
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('action') === 'create') {
            openCreateModal();
        } else if (urlParams.get('edit_id')) {
            const editId = urlParams.get('edit_id');
            const editBtn = document.querySelector(`[onclick*="openEditModal(${editId}"]`);
            if (editBtn) editBtn.click();
        }
    });
</script>
@endsection
