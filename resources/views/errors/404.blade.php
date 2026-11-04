@php
    $title = 'Không tìm thấy trang (404)';
@endphp

@include('clients.blocks.header')
@include('clients.banner')

<!--================ 404 Error Area =================-->
<section class="error_page_area section_gap py-5 text-center" style="background-color: #fbfbfb;">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="error_content p-4 p-md-5 bg-white border rounded shadow-sm">
                    <!-- Icon / Số 404 lớn -->
                    <div class="error_number mb-3">
                        <h1 style="font-size: 110px; font-weight: 800; color: #71cd14; line-height: 1; letter-spacing: -2px; margin: 0;">
                            4<span style="color: #2c3e50;"><i class="fa fa-frown-o"></i></span>4
                        </h1>
                    </div>

                    <h3 class="font-weight-bold mb-3" style="font-size: 24px; color: #2c3e50;">
                        Rất tiếc! Trang hoặc sản phẩm này không tồn tại
                    </h3>

                    <p class="text-muted mb-4 mx-auto" style="max-width: 550px; font-size: 15px; line-height: 1.7;">
                        Đường dẫn bạn vừa truy cập có thể đã bị thay đổi, bị xóa, hoặc sản phẩm đã ngừng kinh doanh trên hệ thống. Bạn có thể tìm kiếm sản phẩm khác hoặc quay về trang chủ.
                    </p>

                    <!-- Khung tìm kiếm sản phẩm nhanh -->
                    <div class="search_404_box mb-4 mx-auto" style="max-width: 480px;">
                        <form action="{{ route('search') }}" method="GET" class="d-flex shadow-sm" style="border-radius: 30px; overflow: hidden; border: 1px solid #e2e8f0;">
                            <input type="text" name="search" class="form-control border-0" placeholder="Nhập tên áo, quần, giày dép muốn tìm..." 
                                style="height: 48px; padding-left: 20px; font-size: 14px;" required>
                            <button type="submit" class="btn px-4 text-white font-weight-bold" 
                                style="background: #71cd14; border: none; height: 48px; line-height: 48px; padding: 0 25px; border-radius: 0; min-width: 110px; font-size: 14px;">
                                Tìm kiếm
                            </button>
                        </form>
                    </div>

                    <!-- Nút bấm điều hướng -->
                    <div class="action_buttons mt-4 d-flex justify-content-center flex-wrap">
                        <a href="{{ route('home') }}" class="main_btn px-4 py-2 mx-2 my-1 font-weight-bold shadow-sm" style="border-radius: 25px; line-height: 38px;">
                            <i class="ti-home mr-2"></i> Về trang chủ
                        </a>
                        <a href="{{ route('product') }}" class="btn btn-outline-dark px-4 py-2 mx-2 my-1 font-weight-bold" style="border-radius: 25px; line-height: 36px; height: 42px;">
                            <i class="ti-shopping-cart mr-2"></i> Khám phá sản phẩm
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--================ End 404 Error Area =================-->

@include('clients.blocks.footer')
