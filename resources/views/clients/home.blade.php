@include('clients.blocks.header')
@include('clients.blocks.banner')

  <!-- Start feature Area -->
  <section class="feature-area section_gap_bottom_custom">
    <div class="container">
      <div class="row">
        <div class="col-lg-3 col-md-6 mb-3">
          <div class="single-feature">
            <a href="#" class="title">
              <i class="flaticon-money"></i>
              <h3>ĐẢM BẢO HOÀN TIỀN</h3>
            </a>
            <p>Cam kết hoàn tiền trong 7 ngày</p>
          </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
          <div class="single-feature">
            <a href="#" class="title">
              <i class="flaticon-truck"></i>
              <h3>GIAO HÀNG MIỄN PHÍ</h3>
            </a>
            <p>Miễn phí đơn từ 500.000đ</p>
          </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
          <div class="single-feature">
            <a href="#" class="title">
              <i class="flaticon-support"></i>
              <h3>HỖ TRỢ 24/7</h3>
            </a>
            <p>Tư vấn tận tâm, chu đáo</p>
          </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
          <div class="single-feature">
            <a href="#" class="title">
              <i class="flaticon-blockchain"></i>
              <h3>THANH TOÁN AN TOÀN</h3>
            </a>
            <p>Bảo mật thông tin tuyệt đối</p>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- End feature Area -->

  <!--================ Feature Product Area =================-->
  <section class="feature_product_area section_gap_bottom_custom">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-12">
          <div class="main_title">
            <h2><span>Sản phẩm nổi bật</span></h2>
            <p>Mặc gì để cuộc sống luôn tươi đẹp?</p>
          </div>
        </div>
      </div>

      <div class="row">
        @foreach ($featuredProductList as $item)
        <div class="col-lg-4 col-md-6">
          <div class="single-product">
            <div class="product-img">
              <span class="product-badge hot-badge"><i class="fa fa-fire"></i> Hot</span>
              <img class="img-fluid" src="{{ asset('assets/admin/img/upload/'.$item->Picture)}}" alt="{{ $item->ProductName }}" />
              <div class="p_icon">
                <a href="{{ route('detail-product', ['id' => $item->ProductID]) }}" title="Xem chi tiết">
                  <i class="ti-eye"></i>
                </a>
                <a href="{{ route('detail-product', ['id' => $item->ProductID]) }}" title="Xem mua hàng">
                  <i class="ti-shopping-cart"></i>
                </a>
              </div>
            </div>
            <div class="product-btm">
              <a href="{{ route('detail-product', ['id' => $item->ProductID]) }}" class="d-block">
                <h4 title="{{ $item->ProductName }}">{{ $item->ProductName }}</h4>
              </a>
              <div class="mt-3 d-flex justify-content-between align-items-center">
                <span class="product-price">{{ number_format($item->Price, 0, ',', '.') }}đ</span>
                @if($item->Quantity > 0)
                  <small class="text-success"><i class="fa fa-check-circle"></i> Còn hàng</small>
                @else
                  <small class="text-danger"><i class="fa fa-times-circle"></i> Hết hàng</small>
                @endif
              </div>
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </section>
  <!--================ End Feature Product Area =================-->

  <!--================ Offer Area =================-->
  <section class="offer_area">
    <div class="container">
      <div class="row justify-content-center">
        <div class="offset-lg-4 col-lg-6 text-center">
          <div class="offer_content">
            <h3 class="text-uppercase mb-40">BỘ SƯU TẬP THỜI TRANG CAO CẤP</h3>
            <h2 class="text-uppercase">ƯU ĐÃI ĐẾN 50%</h2>
            <a href="{{ route('product') }}" class="main_btn mb-20 mt-5">Khám phá ngay</a>
            <p>Số lượng có hạn - Nhanh tay săn ngay!</p>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--================ End Offer Area =================-->

  <!--================ New Product Area =================-->
  <section class="new_product_area section_gap_top section_gap_bottom_custom">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-12">
          <div class="main_title">
            <h2><span>Sản phẩm mới</span></h2>
            <p>Đông này mà muốn đẹp - sang <br>
              Thì xin hãy ghé mua hàng nhà em.</p>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-6">
          <div class="new_product h-100 d-flex flex-column justify-content-center">
            <h5 class="text-uppercase">Bộ sưu tập thời trang</h5>
            <h3 class="text-uppercase">Xu hướng mới nhất</h3>
            <div class="product-img">
              <img class="img-fluid" src="{{ asset('assets/clients/img/product/new-product/new-product1.png')}}" alt="Thời trang mới" />
            </div>
            <h4>Phong cách trẻ trung - Hiện đại</h4>
            <a href="{{ route('product') }}" class="main_btn">Xem tất cả sản phẩm</a>
          </div>
        </div>

        <div class="col-lg-6 mt-5 mt-lg-0">
          <div class="row">
            @foreach($newProductList as $item)
            <div class="col-lg-6 col-md-6">
              <div class="single-product">
                <div class="product-img">
                  <span class="product-badge new-badge">Mới</span>
                  <img class="img-fluid" src="{{ asset('assets/admin/img/upload/'.$item->Picture)}}" alt="{{ $item->ProductName }}" />
                  <div class="p_icon">
                    <a href="{{ route('detail-product', ['id' => $item->ProductID]) }}" title="Xem chi tiết">
                      <i class="ti-eye"></i>
                    </a>
                    <a href="{{ route('detail-product', ['id' => $item->ProductID]) }}" title="Xem mua hàng">
                      <i class="ti-shopping-cart"></i>
                    </a>
                  </div>
                </div>
                <div class="product-btm">
                  <a href="{{ route('detail-product', ['id' => $item->ProductID]) }}" class="d-block">
                    <h4 title="{{ $item->ProductName }}">{{ $item->ProductName }}</h4>
                  </a>
                  <div class="mt-3 d-flex justify-content-between align-items-center">
                    <span class="product-price">{{ number_format($item->Price, 0, ',', '.') }}đ</span>
                    @if($item->Quantity > 0)
                      <small class="text-success"><i class="fa fa-check-circle"></i> Còn hàng</small>
                    @else
                      <small class="text-danger"><i class="fa fa-times-circle"></i> Hết hàng</small>
                    @endif
                  </div>
                </div>
              </div>
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--================ End New Product Area =================-->

  <!--================ Inspired Product Area =================-->
  <section class="inspired_product_area section_gap_bottom_custom">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-12">
          <div class="main_title">
            <h2><span>Sản phẩm giảm giá</span></h2>
            <p>Lá của cây, mây của trời <br>
              Còn chiếc đầm này là của chị em nào đây?</p>
          </div>
        </div>
      </div>

      <div class="row">
        @foreach($discountProductList as $item)
        <div class="col-lg-3 col-md-6">
          <div class="single-product">
            <div class="product-img">
              <span class="product-badge sale-badge">Giảm giá</span>
              <img class="img-fluid" src="{{ asset('assets/admin/img/upload/'.$item->Picture)}}" alt="{{ $item->ProductName }}" />
              <div class="p_icon">
                <a href="{{ route('detail-product', ['id' => $item->ProductID]) }}" title="Xem chi tiết">
                  <i class="ti-eye"></i>
                </a>
                <a href="{{ route('detail-product', ['id' => $item->ProductID]) }}" title="Xem mua hàng">
                  <i class="ti-shopping-cart"></i>
                </a>
              </div>
            </div>
            <div class="product-btm">
              <a href="{{ route('detail-product', ['id' => $item->ProductID]) }}" class="d-block">
                <h4 title="{{ $item->ProductName }}">{{ $item->ProductName }}</h4>
              </a>
              <div class="mt-3 d-flex justify-content-between align-items-center">
                <span class="product-price">{{ number_format($item->Price, 0, ',', '.') }}đ</span>
                <del class="product-old-price">{{ number_format($item->Price * 1.2, 0, ',', '.') }}đ</del>
              </div>
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </section>
  <!--================ End Inspired Product Area =================-->

  @include('clients.blocks.blog')
  @include('clients.blocks.footer')
