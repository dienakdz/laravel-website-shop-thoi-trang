@include('clients.blocks.header')
@include('clients.banner')

<div class="container py-5">
    <div class="section-top-border">
        <div class="row">
            <!-- Cột trái: Cập nhật thông tin cá nhân -->
            <div class="col-lg-7 col-md-7 mb-4 mb-md-0">
                <h3 class="mb-4 title_color font-weight-bold" style="font-size: 20px; color: #2a2a2a;">
                    <i class="ti-user mr-2 text-success"></i> Thông tin cá nhân
                </h3>

                @if (session('msg'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa fa-check-circle mr-2"></i> {{ session('msg') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0 pl-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <form action="{{ route('update-infor') }}" method="POST" class="form-infor" id="form-infor">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="fullname" class="font-weight-bold text-muted small">Họ và tên *</label>
                        <input type="text" name="fullname" id="fullname" placeholder="Nhập họ và tên..."
                            class="form-control" value="{{ old('fullname', $customer->Fullname ?? '') }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-muted small d-block">Giới tính</label>
                        <div class="d-flex align-items-center">
                            <div class="custom-control custom-radio mr-4">
                                <input type="radio" id="male" name="gender" value="male" class="custom-control-input"
                                    {{ (old('gender', $customer->Gender ?? 'male') === 'male') ? 'checked' : '' }}>
                                <label class="custom-control-label" for="male">Nam</label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input type="radio" id="female" name="gender" value="female" class="custom-control-input"
                                    {{ (old('gender', $customer->Gender ?? '') === 'female') ? 'checked' : '' }}>
                                <label class="custom-control-label" for="female">Nữ</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="email" class="font-weight-bold text-muted small">Địa chỉ Email *</label>
                        <input type="email" name="email" id="email" placeholder="example@gmail.com"
                            class="form-control" value="{{ old('email', $customer->Email ?? '') }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="sdt" class="font-weight-bold text-muted small">Số điện thoại *</label>
                        <input type="tel" name="sdt" id="sdt" placeholder="Số điện thoại liên hệ..."
                            class="form-control" value="{{ old('sdt', $customer->Phonenumber ?? '') }}" required>
                    </div>

                    <div class="form-group mb-4">
                        <label for="address" class="font-weight-bold text-muted small">Địa chỉ nhận hàng</label>
                        <textarea name="address" id="address" placeholder="Nhập địa chỉ nhà..." rows="3"
                            class="form-control">{{ old('address', $customer->Address ?? '') }}</textarea>
                    </div>

                    <button type="submit" class="main_btn">Lưu thay đổi</button>
                </form>
            </div>

            <!-- Cột phải: Đổi mật khẩu -->
            <div class="col-lg-5 col-md-5">
                <div class="p-4" style="background: #fdfdfd; border: 1px solid #ebebeb; border-radius: 4px;">
                    <h3 class="mb-4 title_color font-weight-bold" style="font-size: 18px; color: #2a2a2a;">
                        <i class="ti-lock mr-2 text-warning"></i> Đổi mật khẩu
                    </h3>

                    <div id="pass-msg-success" style="display:none;" class="alert alert-success"></div>
                    <div id="pass-msg-error" style="display:none;" class="alert alert-danger"></div>

                    <form action="{{ route('change-password') }}" method="POST" id="form-change-pass">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="oldpass" class="font-weight-bold text-muted small">Mật khẩu hiện tại *</label>
                            <input type="password" name="oldpassword" id="oldpass" placeholder="Nhập mật khẩu hiện tại..."
                                class="form-control" required>
                        </div>

                        <div class="form-group mb-4">
                            <label for="newpass" class="font-weight-bold text-muted small">Mật khẩu mới (Tối thiểu 6 ký tự) *</label>
                            <input type="password" name="newpassword" id="newpass" placeholder="Nhập mật khẩu mới..."
                                class="form-control" minlength="6" required>
                        </div>

                        <button type="submit" class="main_btn w-100 text-center">Cập nhật mật khẩu</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@section('javascript')
    <script>
        $(document).ready(function() {
            // Xử lý đổi mật khẩu qua AJAX
            $('#form-change-pass').on('submit', function(e) {
                e.preventDefault();

                var formData = {
                    'oldpassword': $('#oldpass').val(),
                    'newpassword': $('#newpass').val(),
                    '_token': $('input[name="_token"]').val(),
                };

                $.ajax({
                    type: 'POST',
                    url: $(this).attr('action'),
                    data: formData,
                    success: function(response) {
                        if (response.success === 'success') {
                            $('#form-change-pass')[0].reset();
                            $('#pass-msg-success').text(response.message || 'Đổi mật khẩu thành công!').show();
                            $('#pass-msg-error').hide();
                        } else {
                            $('#pass-msg-success').hide();
                            $('#pass-msg-error').text(response.message || 'Mật khẩu cũ không chính xác!').show();
                        }
                    },
                    error: function(xhr) {
                        $('#pass-msg-success').hide();
                        var errMsg = 'Có lỗi xảy ra, vui lòng kiểm tra lại mật khẩu (tối thiểu 6 ký tự)!';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            var firstErr = Object.values(xhr.responseJSON.errors)[0][0];
                            if (firstErr) errMsg = firstErr;
                        }
                        $('#pass-msg-error').text(errMsg).show();
                    }
                });
            });
        });
    </script>
@endsection

@include('clients.blocks.footer')
