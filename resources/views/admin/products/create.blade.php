@extends('admin.layout')

@section('styles')
<style>
    .form-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 24px;
    }

    .form-group {
        margin-bottom: 16px;
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
        padding: 10px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        font-size: 13px;
        outline: none;
        background: #f8fafc;
        color: var(--text-main);
        transition: all 0.2s;
    }

    .form-input:focus {
        border-color: var(--primary);
        background: #ffffff;
    }

    .upload-dropzone {
        border: 2px dashed #cbd5e1;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        background: #f8fafc;
        cursor: pointer;
        position: relative;
        transition: all 0.2s;
    }

    .upload-dropzone:hover {
        border-color: var(--primary);
        background: var(--primary-light);
    }

    .upload-dropzone input[type="file"] {
        position: absolute;
        inset: 0;
        opacity: 0;
        cursor: pointer;
        width: 100%;
        height: 100%;
    }

    .preview-img-box {
        margin-top: 10px;
        display: none;
    }

    .preview-img-box img {
        max-width: 100%;
        max-height: 180px;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
        object-fit: contain;
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Thêm sản phẩm mới</h1>
        <p class="page-subtitle">Điền thông tin chi tiết và tải ảnh sản phẩm lên hệ thống cửa hàng</p>
    </div>
    <div>
        <a href="{{ route('admin.products.index') }}" class="btn-secondary">
            &larr; Quay lại danh sách
        </a>
    </div>
</div>

@if ($errors->any())
    <div style="background: #fee2e2; border: 1px solid #fecaca; color: #991b1b; padding: 14px 18px; border-radius: 8px; margin-bottom: 20px; font-size: 13px;">
        <strong style="display: block; margin-bottom: 6px;">Vui lòng kiểm tra lại thông tin nhập:</strong>
        <ul style="margin: 0; padding-left: 20px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-grid">
            <!-- CỘT TRÁI: THÔNG TIN CƠ BẢN -->
            <div>
                <div class="form-group">
                    <label class="form-label" for="ProductName">Tên sản phẩm * (tối đa 50 ký tự)</label>
                    <input type="text" name="ProductName" id="ProductName" maxlength="50" class="form-input" 
                           value="{{ old('ProductName') }}" placeholder="Ví dụ: Áo khoác Bomber gió Nam..." required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 14px;">
                    <div class="form-group">
                        <label class="form-label" for="CategoryID">Danh mục *</label>
                        <select name="CategoryID" id="CategoryID" class="form-input" required>
                            <option value="">-- Chọn danh mục --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->CategoryID }}" {{ old('CategoryID') == $cat->CategoryID ? 'selected' : '' }}>
                                    {{ $cat->CategoryName }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="BrandID">Thương hiệu *</label>
                        <select name="BrandID" id="BrandID" class="form-input" required>
                            <option value="">-- Chọn thương hiệu --</option>
                            @foreach($brands as $br)
                                <option value="{{ $br->BrandID }}" {{ old('BrandID') == $br->BrandID ? 'selected' : '' }}>
                                    {{ $br->BrandName }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="ProductStatusID">Phân nhóm hiển thị *</label>
                        <select name="ProductStatusID" id="ProductStatusID" class="form-input" required>
                            @foreach($statuses as $st)
                                <option value="{{ $st->ProductStatusID }}" {{ old('ProductStatusID', 1) == $st->ProductStatusID ? 'selected' : '' }}>
                                    {{ $st->ProductStatusName }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                    <div class="form-group">
                        <label class="form-label" for="Price">Giá bán (VNĐ) *</label>
                        <input type="number" name="Price" id="Price" class="form-input" min="0" step="1000" 
                               value="{{ old('Price') }}" placeholder="Ví dụ: 150000" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="Quantity">Số lượng tồn kho *</label>
                        <input type="number" name="Quantity" id="Quantity" class="form-input" min="0" 
                               value="{{ old('Quantity', 10) }}" placeholder="Ví dụ: 50" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="ProductDesc">Mô tả chi tiết sản phẩm</label>
                    <textarea name="ProductDesc" id="ProductDesc" class="form-input" rows="5" 
                              placeholder="Chất liệu vải, kích thước, form dáng, hướng dẫn bảo quản...">{{ old('ProductDesc') }}</textarea>
                </div>
            </div>

            <!-- CỘT PHẢI: HÌNH ẢNH -->
            <div>
                <div class="form-group">
                    <label class="form-label">Hình ảnh chính sản phẩm *</label>
                    <div class="upload-dropzone">
                        <input type="file" name="Picture" id="Picture" accept="image/*" required onchange="previewImage(this, 'preview-box-1', 'preview-img-1')">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                        <div style="font-weight: 600; font-size: 13px; color: #334155; margin-top: 8px;">Nhấn để chọn ảnh chính</div>
                        <div style="font-size: 11px; color: #94a3b8; margin-top: 4px;">JPG, PNG, WEBP tối đa 2MB</div>
                        <div class="preview-img-box" id="preview-box-1">
                            <img id="preview-img-1" src="" alt="Preview 1">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Hình ảnh phụ (Mặt sau / Góc khác)</label>
                    <div class="upload-dropzone" style="padding: 16px;">
                        <input type="file" name="Picture2" id="Picture2" accept="image/*" onchange="previewImage(this, 'preview-box-2', 'preview-img-2')">
                        <div style="font-weight: 600; font-size: 12px; color: #64748b;">+ Tải lên ảnh phụ (Không bắt buộc)</div>
                        <div class="preview-img-box" id="preview-box-2">
                            <img id="preview-img-2" src="" alt="Preview 2">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div style="border-top: 1px solid #e2e8f0; margin-top: 20px; padding-top: 20px; display: flex; justify-content: flex-end; gap: 10px;">
            <a href="{{ route('admin.products.index') }}" class="btn-secondary">Hủy bỏ</a>
            <button type="submit" class="btn-primary" style="padding: 10px 24px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                <span>Lưu sản phẩm</span>
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    function previewImage(input, boxId, imgId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById(imgId).src = e.target.result;
                document.getElementById(boxId).style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
