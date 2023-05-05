@include('clients.blocks.header')
@include('clients.blocks.banner')
  <!-- Start feature Area -->
  <section class="feature-area section_gap_bottom_custom">
    <div class="container">
      <div class="row">
        <div class="col-lg-3 col-md-6">
          <div class="single-feature">
            <a href="#" class="title">
              <i class="flaticon-money"></i>
              <h3>ĐẢM BẢO HOÀN LẠI TIỀN</h3>
            </a>
            <p>Shall open divide a one</p>
          </div>
        </div>

        <div class="col-lg-3 col-md-6">
          <div class="single-feature">
            <a href="#" class="title">
              <i class="flaticon-truck"></i>
              <h3>GIAO HÀNG MIỄN PHÍ</h3>
            </a>
            <p>Shall open divide a one</p>
          </div>
        </div>

        <div class="col-lg-3 col-md-6">
          <div class="single-feature">
            <a href="#" class="title">
              <i class="flaticon-support"></i>
              <h3>LUÔN ỦNG HỘ</h3>
            </a>
            <p>Shall open divide a one</p>
          </div>
        </div>

        <div class="col-lg-3 col-md-6">
          <div class="single-feature">
            <a href="#" class="title">
              <i class="flaticon-blockchain"></i>
              <h3> THANH TOÁN AN TOÀN</h3>
            </a>
            <p>Shall open divide a one</p>
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
              <img class="img-fluid w-100" src="{{ asset('assets/admin/img/upload/'.$item->Picture)}}" alt="{{ $item->ProductName }}" />
              <div class="p_icon">
                <a href="#">
                  <i class="ti-eye"></i>
                </a>
                <a href="#">
                  <i class="ti-heart"></i>
                </a>
                <a href="#">
                  <i class="ti-shopping-cart"></i>
                </a>
              </div>
            </div>
            <div class="product-btm">
              <a href="{{ route('detail-product', ['id' => $item->ProductID]) }}" class="d-block">
                <h4>{{ $item->ProductName }}</h4>
              </a>
              <div class="mt-3">
                <span class="mr-4">{{ number_format($item->Price) }}</span>
                <del>$35.00</del>
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
            <h3 class="text-uppercase mb-40">all men’s collection</h3>
            <h2 class="text-uppercase">50% off</h2>
            <a href="#" class="main_btn mb-20 mt-5">Discover Now</a>
            <p>Limited Time Offer</p>
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
          <div class="new_product">
            <h5 class="text-uppercase">collection of 2019</h5>
            <h3 class="text-uppercase">Men’s summer t-shirt</h3>
            <div class="product-img">
              <img class="img-fluid" src="{{ asset('assets/clients/img/product/new-product/new-product1.png')}}" alt="" />
            </div>
            <h4>$120.70</h4>
            <a href="#" class="main_btn">Add to cart</a>
          </div>
        </div>

        <div class="col-lg-6 mt-5 mt-lg-0">
          <div class="row">

            @foreach($newProductList as $item)
            <div class="col-lg-6 col-md-6">
              <div class="single-product">
                <div class="product-img">
                  <img class="img-fluid w-100" src="{{ asset('assets/admin/img/upload/'.$item->Picture)}}" alt="{{ $item->ProductName }}" />
                  <div class="p_icon">
                    <a href="#">
                      <i class="ti-eye"></i>
                    </a>
                    <a href="#">
                      <i class="ti-heart"></i>
                    </a>
                    <a href="#">
                      <i class="ti-shopping-cart"></i>
                    </a>
                  </div>
                </div>
                <div class="product-btm">
                  <a href="{{ route('detail-product', ['id' => $item->ProductID]) }}" class="d-block">
                    <h4>{{ $item->ProductName }}</h4>
                  </a>
                  <div class="mt-3">
                    <span class="mr-4">{{ number_format($item->Price) }}</span>
                    <del>$35.00</del>
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
            <h2><span>Sản phảm giảm giá</span></h2>
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
              <img class="img-fluid w-100" src="{{ asset('assets/admin/img/upload/'.$item->Picture)}}" alt="{{ $item->ProductName }}" />
              <div class="p_icon">
                <a href="#">
                  <i class="ti-eye"></i>
                </a>
                <a href="#">
                  <i class="ti-heart"></i>
                </a>
                <a href="#">
                  <i class="ti-shopping-cart"></i>
                </a>
              </div>
            </div>
            <div class="product-btm">
              <a href="{{ route('detail-product', ['id' => $item->ProductID]) }}" class="d-block">
                <h4>{{ $item->ProductName }}</h4>
              </a>
              <div class="mt-3">
                <span class="mr-4">{{ number_format($item->Price) }}</span>
                <del>$35.00</del>
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