@include('clients.blocks.header')
<div class="body_sign_in">
    <div class="container" id="container">
        <div class="form-container sign-in-container" id="signin-form">
            <form action="" method="POST">
                @csrf
                <h1>Đăng nhập</h1>
                <div class="social-container">
                    <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
                    <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
                </div>
                @if (session('msg-login'))
                    <span class="alert alert-danger">{{ session('msg-login') }}</span>
                @endif
                <input type="text" name="username" placeholder="Username" id="username" />
                <input type="password" name="password" placeholder="Password" id="password" />
                <button type="submit">Đăng nhập</button>
            </form>
        </div>
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-right">
                    <h1 style="color: white">Chào Admin!</h1>
                    <p>Nhập thông tin của bạn và bắt đầu quản lý website</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript">
    const signUpButton = document.getElementById('signUp');
    const signInButton = document.getElementById('signIn');

    const signin_form = document.getElementById('signin-form');
    const username = document.getElementById('username');
    const password = document.getElementById('password');

    // add required attribute to necessary fields
    username.required = true;
    password.required = true;

    // add event listener for form submission
    signin_form.addEventListener('submit', function(event) {
        if (!username.checkValidity() || !password.checkValidity()) {
            event.preventDefault();
        }
    });
</script>
</body>

</html>
