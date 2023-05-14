@include('clients.blocks.header')
@include('clients.banner')
<!-- ================ contact section start ================= -->
<section class="section_gap">
    <div class="container">
        <div class="d-none d-sm-block mb-5 pb-4">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3835.74713950596!2d108.24970407479648!3d15.974575984691267!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31421088e365cc75%3A0x6648fdda14970b2c!2zNDcwIMSQxrDhu51uZyBUcuG6p24gxJDhuqFpIE5naMSpYSwgSG_DoCBI4bqjaSwgTmfFqSBIw6BuaCBTxqFuLCDEkMOgIE7hurVuZyA1NTAwMDAsIFZp4buHdCBOYW0!5e0!3m2!1svi!2s!4v1683868484776!5m2!1svi!2s"
                    width="1100" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>

        <div class="row">
            <div class="col-12">
                <h2 class="contact-title">Liên Hệ</h2>
                <p id="message" style="display:none; " class="alert alert-success"></p>
                <p id="error" style="display:none; " class="alert alert-danger"></p>
            </div>
            <div class="col-lg-8 mb-4 mb-lg-0">
                <form class="form-contact contact_form" action="" method="post" id="contactForm">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <textarea class="form-control w-100" name="message" id="message" cols="30" rows="9"
                                    placeholder="Nhập nội dung"></textarea>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <input class="form-control" name="name" id="name" type="text"
                                    placeholder="Họ và tên">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <input class="form-control" name="email" id="email" type="email"
                                    placeholder="Email">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <input class="form-control" name="subject" id="subject" type="text"
                                    placeholder="Tiêu đề">
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt-lg-3">
                        <button type="submit" class="main_btn">Gửi</button>
                    </div>
                </form>
            </div>

            <div class="col-lg-4">
                <div class="media contact-info">
                    <span class="contact-info__icon"><i class="ti-home"></i></span>
                    <div class="media-body">
                        <h3>Ngũ Hành Sơn, Đà Nẵng.</h3>
                        <p>470 Trần Đại Nghĩa</p>
                    </div>
                </div>
                <div class="media contact-info">
                    <span class="contact-info__icon"><i class="ti-tablet"></i></span>
                    <div class="media-body">
                        <h3><a href="tel:454545654">0967468703</a></h3>
                        <p>Luôn luôn hoạt động</p>
                    </div>
                </div>
                <div class="media contact-info">
                    <span class="contact-info__icon"><i class="ti-email"></i></span>
                    <div class="media-body">
                        <h3><a href="mailto:support@colorlib.com">minhdien678@gmail.com</a></h3>
                        <p>Gửi cho chúng tôi truy vấn của bạn bất cứ lúc nào!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ================ contact section end ================= -->
@section('javascript')
    <script>
        // validation form
        const contactForm = document.getElementById('contactForm');
        const nameField = document.getElementById('name');
        const emailField = document.getElementById('email');
        const messageField = document.getElementById('message');
        const subjectField = document.getElementById('subject');

        // add required attribute to necessary fields
        nameField.required = true;
        emailField.required = true;
        messageField.required = true;
        subjectField.required = true;

        // add event listener for form submission
        contactForm.addEventListener('submit', function(event) {
            // check if all required fields are filled

            if (!nameField.checkValidity() || !emailField.checkValidity() || !messageField.checkValidity() || !
                subjectField.checkValidity()) {
                event.preventDefault();
            }
        });
        //Xử lý ajax
        $(document).ready(function() {
            // lắng nghe sự kiện submit form
            $('#contactForm').on('submit', function(e) {
                e.preventDefault(); // ngăn chặn form submit theo cách thông thường

                // lấy thông tin form
                var formData = {
                    'name': $('input[name="name"]').val(),
                    'email': $('input[name="email"]').val(),
                    'subject': $('input[name="subject"]').val(),
                    'message': $('textarea[name="message"]').val(),
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
                        $('#contactForm')[0].reset();

                        // Hiển thị thông báo
                        $('#message').text('Thông tin của bạn đã gửi thành công!').show();
                        $('#error').hide();
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        // Thông báo lỗi
                        $('#message').hide();
                        $('#error').text('Thông tin của bạn đã gửi không thành công!').show();
                    }

                });
            });
        });
    </script>
@endsection
@include('clients.blocks.footer')
