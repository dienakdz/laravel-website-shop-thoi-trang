@include('clients.blocks.header')
<div class="body_sign_in">

    <div class="container" id="container">
        <div class="form-container sign-up-container" id="signup-form-container">
            <form action="{{ route('newUser') }}" method="POST" id="signup-form">
                @csrf
                <h1>Tạo tài khoản</h1>
                <div class="social-container">
                    <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
                    <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
                </div>
                <span>hoặc sử dụng tên đăng nhập mới</span>
                <span id="error" class="alert alert-danger" style="display: none; width: 100%; font-size: 13px;"></span>
                <span id="message" class="alert alert-success" style="display: none; width: 100%; font-size: 13px;"></span>
                @if (session('msg_signup'))
                    <span class="alert alert-danger" style="width: 100%; font-size: 13px;">{{ session('msg_signup') }}</span>
                @endif
                <input type="text" name="username_signup" placeholder="Tên đăng nhập (Username)" id="username_signup" required />
                <input type="password" name="pass_signup" placeholder="Mật khẩu (Password)" id="pass_signup" required />
                <button type="submit">Đăng ký</button>
            </form>
        </div>
        <div class="form-container sign-in-container" id="signin-form-container">
            <form action="{{ route('postLogin') }}" method="POST" id="signin-form">
                @csrf
                <h1>Đăng nhập</h1>
                <div class="social-container">
                    <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
                    <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
                </div>
                <span>Đăng nhập với tài khoản của bạn</span>
                @if (session('msg_login'))
                    <div class="alert alert-warning text-center py-2 px-3 mb-2" style="font-size: 13px; width: 100%;">
                        <i class="fa fa-info-circle mr-1"></i> {{ session('msg_login') }}
                    </div>
                @endif
                @if (session('msg_signin'))
                    <div class="alert alert-danger text-center py-2 px-3 mb-2" style="font-size: 13px; width: 100%;">
                        {{ session('msg_signin') }}
                    </div>
                @endif
                <input type="text" name="username_signin" placeholder="Tên đăng nhập (Username)" id="username_signin" required />
                <input type="password" name="pass_signin" placeholder="Mật khẩu (Password)" id="pass_signin" required />
                <a href="#">Quên mật khẩu?</a>
                <button type="submit">Đăng nhập</button>
            </form>
        </div>
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1 style="color: white">Chào mừng trở lại!</h1>
                    <p>Nếu bạn đã có tài khoản, vui lòng đăng nhập để tiếp tục mua sắm</p>
                    <button class="ghost" id="signIn">Đăng nhập</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1 style="color: white">Xin chào bạn!</h1>
                    <p>Nhập thông tin cá nhân của bạn và bắt đầu hành trình mua sắm cùng chúng tôi</p>
                    <button class="ghost" id="signUp">Đăng ký ngay</button>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script type="text/javascript">
    const signUpButton = document.getElementById('signUp');
    const signInButton = document.getElementById('signIn');
    const container = document.getElementById('container');

    if (signUpButton) {
        signUpButton.addEventListener('click', () => {
            container.classList.add("right-panel-active");
        });
    }

    if (signInButton) {
        signInButton.addEventListener('click', () => {
            container.classList.remove("right-panel-active");
        });
    }

    $(document).ready(function() {
        $('#message').hide();
        $('#error').hide();

        // Lắng nghe sự kiện submit form đăng ký qua Ajax
        $('#signup-form').submit(function(e) {
            e.preventDefault();

            var formData = {
                'username_signup': $('#username_signup').val(),
                'pass_signup': $('#pass_signup').val(),
                '_token': $('input[name="_token"]').val(),
            };

            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: formData,
                success: function(response) {
                    if (response.success) {
                        $('#message').text(response.message || 'Đăng ký thành công! Hãy đăng nhập.').show();
                        $('#error').hide();
                        $('#signup-form')[0].reset();
                        setTimeout(() => {
                            container.classList.remove("right-panel-active");
                        }, 1200);
                    } else {
                        $('#message').hide();
                        $('#error').text(response.message || 'Tên tài khoản đã tồn tại!').show();
                    }
                },
                error: function() {
                    $('#message').hide();
                    $('#error').text('Có lỗi xảy ra, vui lòng thử lại sau!').show();
                }
            });
        });
    });
</script>

</body>

</html>
