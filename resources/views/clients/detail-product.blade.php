@include('clients.blocks.header')
@include('clients.banner')

<!--================Single Product Area =================-->
<div class="product_image_area">
    <div class="container">
        <div class="row s_product_inner">
            <div class="col-lg-6">
                <div class="s_product_img">
                    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active">
                                <img class="img-thunhosize"
                                    src="{{ asset('assets/admin/img/upload/' . $detailProduct->Picture) }}"
                                    alt="{{ $detailProduct->ProductName }}" />
                            </li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="1">
                                <img class="img-thunhosize"
                                    src="{{ asset('assets/admin/img/upload/' . $detailProduct->Picture2) }}"
                                    alt="{{ $detailProduct->ProductName }}" />
                            </li>
                        </ol>
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img class="d-block w-100 img-detailproduct"
                                    src="{{ asset('assets/admin/img/upload/' . $detailProduct->Picture) }}"
                                    alt="{{ $detailProduct->ProductName }}" />
                            </div>
                            <div class="carousel-item">
                                <img class="d-block w-100 img-detailproduct"
                                    src="{{ asset('assets/admin/img/upload/' . $detailProduct->Picture2) }}"
                                    alt="{{ $detailProduct->ProductName }}" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 offset-lg-1">
                <div class="s_product_text">
                    <form action="{{ route('add-cart', ['id' => $detailProduct->ProductID]) }}" method="POST">
                        @csrf
                        <h3>{{ $detailProduct->ProductName }}</h3>
                        <h2>{{ number_format($detailProduct->Price) }}đ</h2>
                        <ul class="list">
                            <li>
                                <a class="active"
                                    href="{{ route('product') }}?category={{ $detailProduct->CategoryID }}">
                                    <span>Danh mục</span> : {{ $detailProduct->CategoryName }}</a>
                            </li>
                            <li>
                                <a href="{{ route('product') }}?category={{ $detailProduct->BrandID }}"> <span>Thương
                                        hiệu</span> : {{ $detailProduct->BrandName }}</a>
                            </li>
                        </ul>
                        {!! $detailProduct->ProductDesc !!}
                        <div class="product_count">
                            <label for="qty">Số lượng:</label>
                            <input type="text" name="cartquantity" id="sst"
                                maxlength="{{ $detailProduct->Quantity }}" value="1" title="Quantity:"
                                class="input-text qty" readonly />
                            <button
                                onclick="var result = document.getElementById('sst'); var sst = parseInt(result.value); if (!isNaN(sst) && sst < {{ $detailProduct->Quantity }}) result.value = sst + 1; return false;"
                                class="increase items-count" type="button">
                                <i class="lnr lnr-chevron-up"></i>
                            </button>
                            <button
                                onclick="var result = document.getElementById('sst'); var sst = parseInt(result.value); if (!isNaN(sst) && sst > 1) result.value = sst - 1; return false;"
                                class="reduced items-count" type="button">
                                <i class="lnr lnr-chevron-down"></i>
                            </button>

                        </div>

                        <div class="card_area">
                            <button type="submit" class="main_btn">Thêm vào giỏ hàng</button>
                            <a class="icon_btn" href="#">
                                <i class="lnr lnr lnr-diamond"></i>
                            </a>
                            <a class="icon_btn" href="#">
                                <i class="lnr lnr lnr-heart"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!--================End Single Product Area =================-->

<!--================Product Description Area =================-->
<section class="product_description_area">
    <div class="container">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
                    aria-selected="true">Mô tả</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" id="review-tab" data-toggle="tab" href="#review" role="tab"
                    aria-controls="review" aria-selected="false">Đánh giá</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade" id="home" role="tabpanel" aria-labelledby="home-tab">
                {!! $detailProduct->ProductDesc !!}
            </div>


            <div class="tab-pane fade show active" id="review" role="tabpanel" aria-labelledby="review-tab">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="row total_rate">
                            <div class="col-6">
                                <div class="box_total">
                                    <h5>Tổng thể</h5>
                                    <h4>{{ round($averageRating, 1) }}</h4>
                                    <h6>({{ $countRating }} đánh giá)</h6>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="rating_list">
                                    <h3>Dựa trên {{ $countRating }} đánh giá</h3>
                                    <ul class="list">
                                        <li>
                                            <a href="#">5 Star
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i></a>
                                        </li>
                                        <li>
                                            <a href="#">4 Star
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i></a>
                                        </li>
                                        <li>
                                            <a href="#">3 Star
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i></a>
                                        </li>
                                        <li>
                                            <a href="#">2 Star
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i></a>
                                        </li>
                                        <li>
                                            <a href="#">1 Star
                                                <i class="fa fa-star"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="review_list">
                            @if (!empty($ratingProduct))
                                <div id="reviews">
                                    @foreach ($ratingProduct as $item)
                                        <div class="review_item">
                                            <div class="media">
                                                <div class="d-flex">
                                                    <img src="{{ asset('assets/clients/img/product/single-product/review-1.png') }}"
                                                        alt="" />
                                                </div>
                                                <div class="media-body">
                                                    <h4>{{ $item->RatingName }}</h4>
                                                    @for ($i = 1; $i <= $item->RatingStar; $i++)
                                                        <i class="fa fa-star"></i>
                                                    @endfor
                                                </div>
                                            </div>
                                            <p>{{ $item->RatingComment }}</p>
                                        </div>
                                    @endforeach
                                </div>

                            @endif
                            <div style="text-align: center; margin-top: 20px;margin-bottom: 20px;">
                                {{ $ratingProduct->links() }}
                            </div>

                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="review_box">
                            <h4>Thêm đánh giá</h4>
                            <div id="message" style="display:none; " class="alert alert-success"></div>
                            <div id="error" style="display:none; " class="alert alert-danger"></div>
                            <p>Đánh giá của bạn:</p>

                            <div id="contact-form-wrapper">
                                <form class="row contact_form"
                                    action="{{ route('rating-product', ['id' => $detailProduct->ProductID]) }}"
                                    method="POST" id="contactForm">
                                    @csrf
                                    <ul class="list" name="star">
                                        <li class="star" data-value="1" value="1">
                                            <i class="fa fa-star"></i>
                                        </li>
                                        <li class="star" data-value="2" value="2">
                                            <i class="fa fa-star"></i>
                                        </li>
                                        <li class="star" data-value="3" value="3">
                                            <i class="fa fa-star"></i>
                                        </li>
                                        <li class="star" data-value="4" value="4">
                                            <i class="fa fa-star"></i>
                                        </li>
                                        <li class="star" data-value="5" value="5">
                                            <i class="fa fa-star"></i>
                                        </li>
                                    </ul>
                                    <input type="hidden" id="rating" name="ratingstar" />
                                    <span class="invalid-feedback"></span>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="name" name="name"
                                                placeholder="Your Full name" />
                                            <span class="invalid-feedback"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="email" class="form-control" id="email" name="email"
                                                placeholder="Email Address" />
                                            <span class="invalid-feedback"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <textarea class="form-control" name="comment" id="comment" rows="1" placeholder="Review"></textarea>
                                            <span class="invalid-feedback"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-12 text-right">
                                        <button type="submit" value="submit" class="btn submit_btn">Đánh
                                            giá</button>
                                    </div>
                                </form>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--================End Product Description Area =================-->
@section('javascript')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('.star').click(function() {
            var value = $(this).attr('data-value');
            $('.star').removeClass('active');
            $('.star').each(function() {
                if ($(this).attr('data-value') <= value) {
                    $(this).addClass('active');
                }
            });
        });

        $('.star').on('click', function() {
            var rating = $(this).attr('value');
            $('#rating').val(rating);
            $('.star').each(function() {
                if ($(this).attr('value') <= rating) {
                    $(this).addClass('selected');
                } else {
                    $(this).removeClass('selected');
                }
            });
        });

        $('ul[name="star"] li').on('click', function() {
            $('ul[name="star"] li').removeClass('active');
            $(this).addClass('active');
            $('ul[name="star"]').attr('value', $(this).attr('value'));
        });


        $(document).ready(function() {
            // lắng nghe sự kiện submit form
            $('#contactForm').on('submit', function(e) {
                e.preventDefault(); // ngăn chặn form submit theo cách thông thường

                // lấy thông tin form
                var formData = {
                    'ratingstar': $('ul[name="star"] li.active').attr('value'),
                    'name': $('input[name="name"]').val(),
                    'email': $('input[name="email"]').val(),
                    'comment': $('textarea[name="comment"]').val(),
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
                        $('#message').text('Đánh giá thành công!').show();
                        $('#error').hide();

                        // Cập nhật lại danh sách đánh giá
                        $('#reviews').load(location.href + ' #reviews');
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        // Thông báo lỗi
                        $('#message').hide();
                        $('#error').text('Đánh giá không thành công!').show();
                    }

                });
            });
        });
        // Bắt sự kiện click vào các trang của phân trang
        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault(); // Ngăn chặn chuyển trang theo cách thông thường

            // Lấy đường dẫn của trang mới
            var pageUrl = $(this).attr('href');

            // Gửi AJAX request để lấy danh sách đánh giá mới
            $.ajax({
                url: pageUrl,
                success: function(response) {
                    // Thay thế nội dung của phân trang và danh sách đánh giá cũ bằng nội dung mới nhận được từ server
                    $('#reviews').html($(response).find('#reviews').html());
                    $('.pagination').html($(response).find('.pagination').html());
                },
                error: function(xhr, textStatus, errorThrown) {
                    // Thông báo lỗi
                    alert('Đã có lỗi xảy ra. Vui lòng thử lại sau!');
                }
            });
        });

        // validation form
        const contactForm = document.getElementById('contactForm');
        const nameField = document.getElementById('name');
        const emailField = document.getElementById('email');
        const commentField = document.getElementById('comment');

        // add required attribute to necessary fields
        nameField.required = true;
        emailField.required = true;
        commentField.required = true;

        // add event listener for form submission
        contactForm.addEventListener('submit', function(event) {
            // check if all required fields are filled

            if (!nameField.checkValidity() || !emailField.checkValidity() || !commentField.checkValidity()) {
                event.preventDefault();
            }
        });
        //không cho số lượng lớn hơn trong giỏ hàng

        const quantityInput = document.querySelector('input[name="quantity_input"]');
        const maxQuantity = parseInt(quantityInput.getAttribute('max'));

        quantityInput.addEventListener('input', function() {
            const value = parseInt(quantityInput.value);

            if (value > maxQuantity) {
                quantityInput.setAttribute('required', 'required');
            } else {
                quantityInput.removeAttribute('required');
            }
        });
    </script>
@endsection
@include('clients.blocks.footer')
