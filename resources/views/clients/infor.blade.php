@include('clients.blocks.header')
@include('clients.banner')
<div class="container">
    <div class="section-top-border">
        <div class="row">
            <div class="col-lg-7 col-md-7">
                <h3 class="mb-30 title_color">Thông tin cá nhân</h3>
                @if (session('msg'))
                    <div class="alert alert-success">{{ session('msg') }}</div>
                @endif
                <form action="{{ route('update-infor') }}" method="POST" class="form-infor" id="form-infor">
                    @csrf
                    @foreach ($inforOld as $item)
                        <div class="mt-10">
                            <input type="text" name="fullname" id="fullname" placeholder="Họ và tên *"
                                class="single-input-primary" value="{{ $item->Fullname }}">
                        </div>
                        <div class="mt-10">
                            <input type="radio" name="gender" id="male" value="male"
                                @if ($item->Gender == 'male') checked @endif>
                            <label for="male">Nam</label>
                            &emsp;
                            <input type="radio" name="gender" id="female" value="female"
                                @if ($item->Gender == 'female') checked @endif>
                            <label for="male">Nữ</label>
                        </div>
                        <div class="mt-10">
                            <input type="email" name="email" id="email" placeholder="Email *"
                                class="single-input-primary" value="{{ $item->Email }}">
                        </div>
                        <div class="mt-10">
                            <input type="address" name="address" id="address" placeholder="Địa chỉ *"
                                class="single-input-primary" value="{{ $item->Address }}">
                        </div>
                        <div class="mt-10">
                            <input type="number" name="sdt" id="sdt" placeholder="Số điện thoại *"
                                class="single-input-primary" value="{{ $item->Phonenumber }}">
                        </div>
                        <button type="submit" class="main_btn">Cập nhật</button>
                    @endforeach
                </form>
            </div>
            <div class="col-lg-4 col-md-4 mt-sm-30 element-wrap">
                <div class="single-element-widget">
                    <h3 class="mb-30 title_color">Đổi mật khẩu</h3>
                    <p id="message" style="display:none; " class="alert alert-success"></p>
                    <p id="error" style="display:none; " class="alert alert-danger"></p>
                    <form action="{{ route('change-password') }}" method="POST" class="form-infor"
                        id="form-change-pass">
                        @csrf
                        <div class="mt-10">
                            <input type="password" name="oldpassword" id="oldpass" placeholder="Mật khẩu cũ *"
                                class="single-input-primary">
                        </div>
                        <div class="mt-10">
                            <input type="password" name="newpassword" id="newpass" placeholder="Mật khẩu mới *"
                                class="single-input-primary">
                        </div>
                        <button type="submit" class="main_btn">Đổi mật khẩu</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@section('javascript')
    <script>
        // validation form
        const inforForm = document.getElementById('form-infor');
        const fullnameField = document.getElementById('fullname');
        const emailField = document.getElementById('email');
        const addressField = document.getElementById('address');
        const sdtField = document.getElementById('sdt');

        const changepassForm = document.getElementById('form-change-pass');
        const oldpassField = document.getElementById('oldpass');
        const newpassField = document.getElementById('newpass');

        // add required attribute to necessary fields
        fullnameField.required = true;
        emailField.required = true;
        addressField.required = true;
        sdtField.required = true;
        oldpassField.required = true;
        newpassField.required = true;

        // add event listener for form submission
        inforForm.addEventListener('submit', function(event) {
            // check if all required fields are filled

            if (!fullnameField.checkValidity() || !emailField.checkValidity() || !addressField.checkValidity() || !
                sdtField.checkValidity()) {
                event.preventDefault();
            }
        });
        changepassForm.addEventListener('submit', function(event) {
            if (!oldpassField.checkValidity() || !newpassField.checkValidity()) {
                event.preventDefault();
            }
        });

        //Xử lý ajax
        $(document).ready(function() {
            // lắng nghe sự kiện submit form
            $('#form-change-pass').on('submit', function(e) {
                e.preventDefault(); // ngăn chặn form submit theo cách thông thường

                // lấy thông tin form
                var formData = {
                    'oldpassword': $('input[name="oldpassword"]').val(),
                    'newpassword': $('input[name="newpassword"]').val(),
                    '_token': $('input[name="_token"]').val(),
                };
                console.log(formData);
                // gửi ajax request
                $.ajax({
                    type: 'POST',
                    url: $(this).attr('action'),
                    data: formData,
                    success: function(response) {
                        // Xóa nội dung của form
                        $('#form-change-pass')[0].reset();
                        var checkResponse = response.success;
                        if (checkResponse == 'success') {
                            //Hiển thị thông báo
                            $('#message').text('Đổi mật khẩu thành công!').show();
                            $('#error').hide();
                        } else {
                            $('#message').hide();
                            $('#error').text('Mật khẩu cũ không đúng!').show();
                        }
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        // Thông báo lỗi
                        $('#message').hide();
                        $('#error').text('Có lỗi xảy ra!').show();
                    }
                });
            });
        });
    </script>
@endsection

@include('clients.blocks.footer')
