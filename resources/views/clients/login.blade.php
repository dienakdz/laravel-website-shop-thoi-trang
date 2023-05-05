@include('clients.blocks.header')
<div class="body_sign_in">

    <div class="container" id="container">
        <div class="form-container sign-up-container" id="signup-form">
            <form action="" method="POST">
                @csrf
                <div class="social-container">
                    <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
                    <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
                </div>
                <span>hoặc sử dụng tài khoản của bạn</span>
                <span id="error" class="alert alert-danger"></span>
                <span id="message" class="alert alert-success"></span>
                @if (session('msg_signup'))
                    <span class="alert alert-danger" id="error">{{ session('msg_signup') }}</span>
                @endif
                <input type="text" name="username_signup" placeholder="Username" id="username_signup" />
                <input type="password" name="pass_signup" placeholder="Password" id="pass_signup" />
                <button>Đăng kí</button>
            </form>
        </div>
        <div class="form-container sign-in-container" id="signin-form">
            <form action="postLogin" method="POST">
                @csrf
                <h1>Đăng nhập</h1>
                <div class="social-container">
                    <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
                    <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
                </div>
                <span>hoặc sử dụng tài khoản của bạn</span>
                @if (session('msg_signin'))
                    <span class="alert alert-danger">{{ session('msg_signin') }}</span>
                @endif
                <input type="text" name="username_signin" placeholder="Username" id="username_signin" />
                <input type="password" name="pass_signin" placeholder="Password" id="pass_signin" />
                <a href="#">Quên mật khẩu?</a>
                <button type="submit">Đăng nhập</button>
            </form>
        </div>
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1 style="color: white">Chào mừng trở lại</h1>
                    <p>Để giữ kết nối với chúng tôi, vui lòng đăng nhập bằng thông tin cá nhân của bạn</p>
                    <button class="ghost" id="signIn">Đăng nhập</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1 style="color: white">Chào bạn!</h1>
                    <p>Nhập thông tin cá nhân của bạn và bắt đầu hành trình với chúng tôi</p>
                    <button class="ghost" id="signUp">Đăng kí</button>
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

    signUpButton.addEventListener('click', () => {
        container.classList.add("right-panel-active");
    });

    signInButton.addEventListener('click', () => {
        container.classList.remove("right-panel-active");
    });


    const signin_form = document.getElementById('signin-form');
    const username_signin = document.getElementById('username_signin');
    const pass_signin = document.getElementById('pass_signin');


    // add required attribute to necessary fields
    username_signin.required = true;
    pass_signin.required = true;


    // add event listener for form submission
    signin_form.addEventListener('submit', function(event) {
        // check if all required fields are filled

        if (!username_signin.checkValidity() || !pass_signin.checkValidity()) {
            event.preventDefault();
        }
    });

    const signup_form = document.getElementById('signup-form');
    const username_signup = document.getElementById('username_signup');
    const pass_signup = document.getElementById('pass_signup');


    // add required attribute to necessary fields
    username_signup.required = true;
    pass_signup.required = true;


    // add event listener for form submission
    signup_form.addEventListener('submit', function(event) {
        // check if all required fields are filled

        if (!username_signup.checkValidity() || !pass_signup.checkValidity()) {
            event.preventDefault();
        }
    });

    $(document).ready(function() {
        $('#message').hide();
        $('#error').hide();
        // Lắng nghe sự kiện submit form
        $('#signup-form').submit(function(e) {
            // Ngăn chặn hành vi mặc định của form
            e.preventDefault();

            // Lấy thông tin form và đóng gói thành object
            var formData = {
                'username_signup': $('#username_signup').val(),
                'pass_signup': $('#pass_signup').val(),
                '_token': $('input[name="_token"]').val(),
            };
            console.log(formData);
            // Gửi AJAX request
            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: formData,
                success: function(response) {
                    // Xử lý kết quả trả về từ server
                    // Ví dụ: hiển thị thông báo thành công
                    console.log(response.message);
                    if (response.success) {
                        $('#message').text(response.message).show();
                        $('#error').hide();
                    } else {
                        $('#message').hide();
                        $('#error').text('Tên tài khoản đã tồn tại!').show();
                    }
                },
                error: function(xhr, textStatus, errorThrown) {
                    alert("có lỗi xảy ra!");
                }
            });
        });
    });
</script>

</body>

</html>
