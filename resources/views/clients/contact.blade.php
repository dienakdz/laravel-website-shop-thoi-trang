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
                <h2 class="contact-title">Liên Hệ Với Chúng Tôi</h2>
                <p id="contact-alert-msg" style="display:none;" class="alert alert-success"></p>
                <p id="contact-error-msg" style="display:none;" class="alert alert-danger"></p>
            </div>
            <div class="col-lg-8 mb-4 mb-lg-0">
                <form class="form-contact contact_form" action="{{ route('post-contact') }}" method="post" id="contactForm">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <textarea class="form-control w-100" name="message" id="contact_message" cols="30" rows="7"
                                    placeholder="Nội dung liên hệ hoặc góp ý *" required></textarea>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <input class="form-control" name="name" id="contact_name" type="text"
                                    placeholder="Họ và tên của bạn *" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <input class="form-control" name="email" id="contact_email" type="email"
                                    placeholder="Địa chỉ Email *" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <input class="form-control" name="subject" id="contact_subject" type="text"
                                    placeholder="Tiêu đề thư *" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt-lg-3">
                        <button type="submit" class="main_btn">Gửi thông điệp</button>
                    </div>
                </form>
            </div>

            <div class="col-lg-4">
                <div class="media contact-info">
                    <span class="contact-info__icon"><i class="ti-home"></i></span>
                    <div class="media-body">
                        <h3>Ngũ Hành Sơn, Đà Nẵng</h3>
                        <p>470 Trần Đại Nghĩa</p>
                    </div>
                </div>
                <div class="media contact-info">
                    <span class="contact-info__icon"><i class="ti-tablet"></i></span>
                    <div class="media-body">
                        <h3><a href="tel:0967468703">0967.468.703</a></h3>
                        <p>Hỗ trợ từ 8h00 - 21h30 hằng ngày</p>
                    </div>
                </div>
                <div class="media contact-info">
                    <span class="contact-info__icon"><i class="ti-email"></i></span>
                    <div class="media-body">
                        <h3><a href="mailto:support@shopthoitrang.vn">support@shopthoitrang.vn</a></h3>
                        <p>Gửi thư cho chúng tôi bất cứ lúc nào!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ================ contact section end ================= -->

@section('javascript')
    <script>
        $(document).ready(function() {
            $('#contactForm').on('submit', function(e) {
                e.preventDefault();

                var formData = {
                    'name': $('#contact_name').val(),
                    'email': $('#contact_email').val(),
                    'subject': $('#contact_subject').val(),
                    'message': $('#contact_message').val(),
                    '_token': $('input[name="_token"]').val(),
                };

                $.ajax({
                    type: 'POST',
                    url: $(this).attr('action'),
                    data: formData,
                    success: function(response) {
                        $('#contactForm')[0].reset();
                        $('#contact-alert-msg').text('Cảm ơn bạn! Thông tin liên hệ đã được gửi thành công. Chúng tôi sẽ phản hồi sớm nhất có thể.').show();
                        $('#contact-error-msg').hide();
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        $('#contact-alert-msg').hide();
                        $('#contact-error-msg').text('Có lỗi xảy ra khi gửi thông điệp. Vui lòng thử lại sau!').show();
                    }
                });
            });
        });
    </script>
@endsection

@include('clients.blocks.footer')
