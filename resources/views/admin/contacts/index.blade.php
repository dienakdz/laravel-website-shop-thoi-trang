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

    /* MODAL */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.6);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 100;
        padding: 20px;
    }

    .modal-content {
        background: #ffffff;
        border-radius: 10px;
        max-width: 600px;
        width: 100%;
        padding: 24px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Hòm thư Liên hệ & Phản hồi</h1>
        <p class="page-subtitle">
            Tổng cộng <strong>{{ $totalContacts }}</strong> tin nhắn gửi từ form Liên hệ
            @if($unreadContacts > 0)
                • <span style="color:#d97706; font-weight:600;">{{ $unreadContacts }} tin chưa xử lý</span>
            @endif
            • <span style="color:#10b981;">{{ $readContacts }} đã xử lý</span>
        </p>
    </div>
</div>

<div class="card">
    <!-- TOOLBAR -->
    <form method="GET" action="{{ route('admin.contacts.index') }}" class="filter-toolbar">
        <div class="filter-left">
            <div style="position: relative; width: 280px;">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Họ tên, email, tiêu đề, nội dung..." class="filter-select" style="width: 100%; padding-left: 32px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%);">
                    <circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </div>

            <select name="status" class="filter-select" onchange="this.form.submit()">
                <option value="all">Trạng thái: Tất cả</option>
                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Chưa xử lý (Mới)</option>
                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Đã xử lý</option>
            </select>

            <button type="submit" class="btn-secondary" style="padding: 7px 12px;">Lọc</button>
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.contacts.index') }}" class="btn-secondary" style="color: #64748b;">Xóa lọc</a>
            @endif
        </div>

        <div style="font-size: 13px; color: var(--text-muted);">
            Hiển thị <strong>{{ $contacts->count() }}</strong> / <strong>{{ $contacts->total() }}</strong> tin nhắn
        </div>
    </form>

    <!-- BẢNG LIÊN HỆ -->
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Mã</th>
                    <th>Người gửi & Email</th>
                    <th>Tiêu đề phản hồi</th>
                    <th>Nội dung tóm tắt</th>
                    <th>Ngày gửi</th>
                    <th>Trạng thái</th>
                    <th style="text-align: right;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($contacts as $msg)
                    <tr style="{{ $msg->Status == '0' ? 'background: rgba(245, 158, 11, 0.03);' : '' }}">
                        <td>
                            <strong style="color: #0f172a; font-size: 13px;">#LH-{{ $msg->ContactID }}</strong>
                        </td>
                        <td>
                            <div style="font-weight: 600; color: #0f172a; font-size: 14px;">{{ !empty($msg->FullName) ? $msg->FullName : 'Khách vãng lai' }}</div>
                            <div style="font-size: 12px; color: #64748b;">
                                @if(!empty($msg->Email))
                                    <a href="mailto:{{ $msg->Email }}" style="color: inherit; text-decoration: none;">{{ $msg->Email }}</a>
                                @else
                                    <span>Chưa có email</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 600; font-size: 13px; color: #1e293b; max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $msg->Title }}">
                                {{ !empty($msg->Title) ? $msg->Title : '(Không có tiêu đề)' }}
                            </div>
                        </td>
                        <td>
                            <div style="font-size: 12px; color: #475569; max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                {{ !empty($msg->Content) ? $msg->Content : '(Nội dung trống)' }}
                            </div>
                        </td>
                        <td style="font-size: 12px; color: #64748b;">
                            {{ !empty($msg->ContactDate) ? $msg->ContactDate : '---' }}
                        </td>
                        <td>
                            @if($msg->Status == '0')
                                <span class="status-pill status-pending"><span class="dot"></span>Chưa xử lý</span>
                            @else
                                <span class="status-pill status-completed"><span class="dot"></span>Đã xử lý</span>
                            @endif
                        </td>
                        <td style="text-align: right;">
                            <div style="display: inline-flex; align-items: center; gap: 6px;">
                                <button class="btn-icon-sm" title="Xem chi tiết nội dung" 
                                        onclick="showContactModal('{{ addslashes($msg->FullName) }}', '{{ addslashes($msg->Email) }}', '{{ addslashes($msg->Title) }}', '{{ addslashes(str_replace(["\r", "\n"], ' ', $msg->Content)) }}', '{{ $msg->ContactDate }}')">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                </button>

                                <form action="{{ route('admin.contacts.mark-read', $msg->ContactID) }}" method="POST" style="margin: 0;">
                                    @csrf
                                    @if($msg->Status == '0')
                                        <button type="submit" class="btn-primary" style="padding: 5px 8px; font-size: 12px;" title="Đánh dấu đã xử lý">
                                            Xử lý
                                        </button>
                                    @else
                                        <button type="submit" class="btn-secondary" style="padding: 5px 8px; font-size: 12px;" title="Đánh dấu chưa xử lý">
                                            Chưa đọc
                                        </button>
                                    @endif
                                </form>

                                <form action="{{ route('admin.contacts.destroy', $msg->ContactID) }}" method="POST" style="margin: 0;"
                                      onsubmit="return confirm('Bạn có chắc muốn xóa tin nhắn này không?');">
                                    @csrf
                                    <button type="submit" class="btn-icon-sm delete" title="Xóa tin nhắn">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 36px; color: #94a3b8;">
                            Hòm thư không có tin nhắn nào.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- PHÂN TRANG -->
    @if($contacts->hasPages())
        <div style="display: flex; justify-content: flex-end; margin-top: 20px;">
            {{ $contacts->links() }}
        </div>
    @endif
</div>

<!-- MODAL XEM CHI TIẾT TIN NHẮN -->
<div class="modal-overlay" id="contactModal">
    <div class="modal-content">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e2e8f0; padding-bottom: 12px; margin-bottom: 16px;">
            <h3 style="font-size: 16px; font-weight: 700; color: #0f172a; margin: 0;">📨 Nội dung tin nhắn liên hệ</h3>
            <span style="font-size: 20px; cursor: pointer; color: #64748b;" onclick="closeContactModal()">&times;</span>
        </div>

        <div style="font-size: 13px; line-height: 1.8; color: #334155; margin-bottom: 16px;">
            <div><strong>Người gửi:</strong> <span id="m_name"></span></div>
            <div><strong>Email:</strong> <span id="m_email"></span></div>
            <div><strong>Thời gian gửi:</strong> <span id="m_date" style="color: #64748b;"></span></div>
            <div><strong>Chủ đề:</strong> <span id="m_title" style="font-weight: 600; color: #0f172a;"></span></div>
        </div>

        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 14px; font-size: 13px; line-height: 1.6; color: #1e293b; max-height: 250px; overflow-y: auto;">
            <strong>Nội dung phản hồi:</strong>
            <p id="m_content" style="margin: 8px 0 0 0; white-space: pre-wrap;"></p>
        </div>

        <div style="display: flex; justify-content: flex-end; margin-top: 20px; gap: 10px;">
            <a id="m_reply_link" href="#" class="btn-primary" style="padding: 7px 16px;">
                Phản hồi qua Email
            </a>
            <button class="btn-secondary" onclick="closeContactModal()">Đóng</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function showContactModal(name, email, title, content, date) {
        document.getElementById('m_name').innerText = name;
        document.getElementById('m_email').innerText = email;
        document.getElementById('m_date').innerText = date;
        document.getElementById('m_title').innerText = title;
        document.getElementById('m_content').innerText = content;
        document.getElementById('m_reply_link').href = 'mailto:' + email + '?subject=Phản hồi từ DDien Fashion: ' + encodeURIComponent(title);
        document.getElementById('contactModal').style.display = 'flex';
    }

    function closeContactModal() {
        document.getElementById('contactModal').style.display = 'none';
    }
</script>
@endsection
