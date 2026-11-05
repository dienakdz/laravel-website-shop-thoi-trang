@include('clients.blocks.header')
@include('clients.banner')

<!--================Single Product Area =================-->
<div class="product_image_area py-5">
    <div class="container">
        <div class="row s_product_inner">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="s_product_img">
                    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner shadow-sm" style="border-radius: 8px; background: #fdfdfd; border: 1px solid #edf2f7; height: 460px; display: flex; align-items: center; justify-content: center;">
                            <div class="carousel-item active h-100" style="display: flex; align-items: center; justify-content: center;">
                                <img class="img-fluid" style="max-height: 420px; max-width: 100%; object-fit: contain;"
                                    src="{{ asset('assets/admin/img/upload/' . $detailProduct->Picture) }}"
                                    alt="{{ $detailProduct->ProductName }}" />
                            </div>
                            @if (!empty($detailProduct->Picture2))
                                <div class="carousel-item h-100" style="display: flex; align-items: center; justify-content: center;">
                                    <img class="img-fluid" style="max-height: 420px; max-width: 100%; object-fit: contain;"
                                        src="{{ asset('assets/admin/img/upload/' . $detailProduct->Picture2) }}"
                                        alt="{{ $detailProduct->ProductName }}" />
                                </div>
                            @endif
                        </div>
                        <ol class="carousel-indicators position-static mt-3 justify-content-center">
                            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active mx-2" style="width: 70px; height: 70px; text-indent: 0; background: none; border: 2px solid #71cd14; border-radius: 6px; padding: 3px; overflow: hidden;">
                                <img style="width: 100%; height: 100%; object-fit: contain;"
                                    src="{{ asset('assets/admin/img/upload/' . $detailProduct->Picture) }}"
                                    alt="{{ $detailProduct->ProductName }}" />
                            </li>
                            @if (!empty($detailProduct->Picture2))
                                <li data-target="#carouselExampleIndicators" data-slide-to="1" class="mx-2" style="width: 70px; height: 70px; text-indent: 0; background: none; border: 2px solid #ddd; border-radius: 6px; padding: 3px; overflow: hidden;">
                                    <img style="width: 100%; height: 100%; object-fit: contain;"
                                        src="{{ asset('assets/admin/img/upload/' . $detailProduct->Picture2) }}"
                                        alt="{{ $detailProduct->ProductName }}" />
                                </li>
                            @endif
                        </ol>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 offset-lg-1">
                <div class="s_product_text">
                    <form action="{{ route('add-cart', ['id' => $detailProduct->ProductID]) }}" method="POST">
                        @csrf
                        <h3 class="font-weight-bold" style="font-size: 26px; color: #2c3e50;">{{ $detailProduct->ProductName }}</h3>
                        <h2 class="my-3 font-weight-bold" style="color: #e74c3c; font-size: 28px;">{{ number_format($detailProduct->Price, 0, ',', '.') }}đ</h2>
                        <ul class="list mb-3" style="line-height: 2;">
                            <li>
                                <a class="active text-dark" href="{{ route('product') }}?category={{ $detailProduct->CategoryID }}">
                                    <span class="text-muted font-weight-normal">Danh mục:</span> <strong class="text-success">{{ $detailProduct->CategoryName }}</strong>
                                </a>
                            </li>
                            <li>
                                <a class="text-dark" href="{{ route('product') }}?brand={{ $detailProduct->BrandID }}">
                                    <span class="text-muted font-weight-normal">Thương hiệu:</span> <strong>{{ $detailProduct->BrandName }}</strong>
                                </a>
                            </li>
                            <li>
                                <span class="text-muted font-weight-normal">Tình trạng kho:</span>
                                @if ($detailProduct->Quantity > 0)
                                    <span class="badge badge-success px-2 py-1">Còn hàng ({{ $detailProduct->Quantity }} sản phẩm)</span>
                                @else
                                    <span class="badge badge-danger px-2 py-1">Tạm hết hàng</span>
                                @endif
                            </li>
                        </ul>
                        <div class="product_desc mb-4 text-muted" style="line-height: 1.6;">
                            {!! $detailProduct->ProductDesc !!}
                        </div>

                        @if ($detailProduct->Quantity > 0)
                            <div class="product_count d-flex align-items-center mb-4">
                                <label for="qty" class="mr-3 font-weight-bold mb-0">Số lượng:</label>
                                <div class="d-inline-flex align-items-center">
                                    <button class="btn btn-outline-secondary btn-sm px-3" type="button"
                                        onclick="var qty = document.getElementById('sst'); var val = parseInt(qty.value); if(val > 1) qty.value = val - 1;">
                                        <i class="fa fa-minus"></i>
                                    </button>
                                    <input type="number" name="cartquantity" id="sst" min="1" max="{{ $detailProduct->Quantity }}" value="1"
                                        class="form-control text-center mx-2 font-weight-bold" style="width: 70px;" />
                                    <button class="btn btn-outline-secondary btn-sm px-3" type="button"
                                        onclick="var qty = document.getElementById('sst'); var val = parseInt(qty.value); if(val < {{ $detailProduct->Quantity }}) qty.value = val + 1;">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="card_area d-flex align-items-center">
                                <button type="submit" class="main_btn px-4 py-3 font-weight-bold shadow-sm" style="border-radius: 25px;">
                                    <i class="ti-shopping-cart mr-2"></i> Thêm vào giỏ hàng
                                </button>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="fa fa-exclamation-triangle mr-1"></i> Sản phẩm hiện đang tạm hết hàng. Quý khách vui lòng chọn sản phẩm khác hoặc quay lại sau!
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!--================End Single Product Area =================-->

<!--================Product Description Area =================-->
<section class="product_description_area py-5 bg-light">
    <div class="container">
        <ul class="nav nav-tabs justify-content-center border-bottom-0" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active px-4 py-2 font-weight-bold" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">
                    Mô tả chi tiết
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link px-4 py-2 font-weight-bold" id="review-tab" data-toggle="tab" href="#review" role="tab" aria-controls="review" aria-selected="false">
                    Đánh giá & Bình luận ({{ $countRating }})
                </a>
            </li>
        </ul>
        <div class="tab-content bg-white p-4 p-md-5 rounded shadow-sm border mt-3" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div class="content-detail-desc" style="line-height: 1.8; color: #444;">
                    {!! $detailProduct->ProductDesc !!}
                </div>
            </div>

            <div class="tab-pane fade" id="review" role="tabpanel" aria-labelledby="review-tab">
                <div class="row">
                    <div class="col-lg-6 pr-lg-5 mb-5 mb-lg-0">
                        <div class="row total_rate mb-4 align-items-center p-3 bg-light rounded">
                            <div class="col-6 text-center border-right">
                                <div class="box_total">
                                    <h5 class="text-muted mb-1">Điểm trung bình</h5>
                                    <h2 class="text-warning font-weight-bold mb-1">{{ round($averageRating, 1) }} / 5</h2>
                                    <p class="text-muted small m-0">({{ $countRating }} lượt đánh giá)</p>
                                </div>
                            </div>
                            <div class="col-6 text-center">
                                <div class="rating_stars">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="fa fa-star {{ $i <= round($averageRating) ? 'text-warning' : 'text-muted' }}" style="font-size: 20px;"></i>
                                    @endfor
                                </div>
                            </div>
                        </div>

                        <div class="review_list">
                            <h5 class="font-weight-bold mb-3">Nhận xét từ khách hàng</h5>
                            @if (!empty($ratingProduct) && count($ratingProduct) > 0)
                                <div id="reviews">
                                    @foreach ($ratingProduct as $item)
                                        <div class="review_item p-3 mb-3 border-bottom">
                                            <div class="media align-items-center mb-2">
                                                <div class="mr-3">
                                                    <span class="badge badge-secondary p-2 rounded-circle font-weight-bold" style="width: 38px; height: 38px; line-height: 22px; display: inline-block; text-align: center;">
                                                        {{ strtoupper(substr($item->RatingName, 0, 1)) }}
                                                    </span>
                                                </div>
                                                <div class="media-body">
                                                    <h6 class="font-weight-bold mb-0 text-dark">{{ $item->RatingName }}</h6>
                                                    <div>
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            <i class="fa fa-star {{ $i <= $item->RatingStar ? 'text-warning' : 'text-muted' }} small"></i>
                                                        @endfor
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="text-muted mb-0 pl-5" style="font-size: 14px;">{{ $item->RatingComment }}</p>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-3">
                                    {{ $ratingProduct->links() }}
                                </div>
                            @else
                                <p class="text-muted font-italic">Chưa có đánh giá nào cho sản phẩm này. Hãy là người đầu tiên đánh giá!</p>
                            @endif
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="review_box p-4 border rounded bg-light">
                            <h5 class="font-weight-bold mb-3">Gửi đánh giá của bạn</h5>
                            <div id="message" style="display:none;" class="alert alert-success"></div>
                            <div id="error" style="display:none;" class="alert alert-danger"></div>

                            <form class="contact_form" action="{{ route('rating-product', ['id' => $detailProduct->ProductID]) }}" method="POST" id="contactForm">
                                @csrf
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold mb-1">Chọn số sao:</label>
                                    <ul class="list-inline mb-0 star-rating" name="star" style="font-size: 22px; cursor: pointer;">
                                        <li class="list-inline-item star active" data-value="1" value="1"><i class="fa fa-star text-warning"></i></li>
                                        <li class="list-inline-item star active" data-value="2" value="2"><i class="fa fa-star text-warning"></i></li>
                                        <li class="list-inline-item star active" data-value="3" value="3"><i class="fa fa-star text-warning"></i></li>
                                        <li class="list-inline-item star active" data-value="4" value="4"><i class="fa fa-star text-warning"></i></li>
                                        <li class="list-inline-item star active" data-value="5" value="5"><i class="fa fa-star text-warning"></i></li>
                                    </ul>
                                    <input type="hidden" id="rating" name="ratingstar" value="5" />
                                </div>

                                <div class="form-group mb-3">
                                    <label class="font-weight-bold small mb-1">Họ và tên <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="Nhập họ và tên..." value="{{ session('username') ?? '' }}" required />
                                </div>

                                <div class="form-group mb-3">
                                    <label class="font-weight-bold small mb-1">Email</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        placeholder="Nhập địa chỉ email (tùy chọn)..." />
                                </div>

                                <div class="form-group mb-3">
                                    <label class="font-weight-bold small mb-1">Nội dung đánh giá <span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="comment" id="comment" rows="3"
                                        placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm này..." required></textarea>
                                </div>

                                <button type="submit" class="btn btn-success px-4 font-weight-bold">
                                    Gửi đánh giá
                                </button>
                            </form>
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
        $(document).ready(function() {
            // Đổi ảnh active thumbnail khi click
            $('.carousel-indicators li').click(function() {
                $('.carousel-indicators li').css('border-color', '#ddd');
                $(this).css('border-color', '#71cd14');
            });

            // Xử lý chọn sao đánh giá
            $('.star').click(function() {
                var value = parseInt($(this).attr('data-value'));
                $('#rating').val(value);
                $('.star').each(function() {
                    var starVal = parseInt($(this).attr('data-value'));
                    if (starVal <= value) {
                        $(this).find('i').removeClass('text-muted').addClass('text-warning');
                    } else {
                        $(this).find('i').removeClass('text-warning').addClass('text-muted');
                    }
                });
            });

            // Gửi đánh giá qua Ajax
            $('#contactForm').on('submit', function(e) {
                e.preventDefault();

                var formData = {
                    'ratingstar': $('#rating').val() || 5,
                    'name': $('#name').val(),
                    'email': $('#email').val(),
                    'comment': $('#comment').val(),
                    '_token': $('input[name="_token"]').val(),
                };

                $.ajax({
                    type: 'POST',
                    url: $(this).attr('action'),
                    data: formData,
                    success: function(response) {
                        $('#contactForm')[0].reset();
                        $('#message').text(response.message || 'Đánh giá thành công!').show();
                        $('#error').hide();
                        setTimeout(() => {
                            location.reload();
                        }, 1200);
                    },
                    error: function() {
                        $('#message').hide();
                        $('#error').text('Có lỗi xảy ra khi gửi đánh giá, vui lòng thử lại!').show();
                    }
                });
            });
        });
    </script>
@endsection

@include('clients.blocks.footer')
