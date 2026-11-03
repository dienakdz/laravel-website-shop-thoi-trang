<!--================Home Banner Area =================-->
@foreach ($banner as $item)
  <section class="home_banner_area mb-40" style="background-image: url('{{asset('assets/admin/img/'.$item->image_banner)}}')">
    <div class="banner_inner d-flex align-items-center">
      <div class="container">
        <div class="banner_content row">
          <div class="col-lg-12">
            <p class="sub text-uppercase">Thời trang xu hướng mới</p>
            <h3><span>{!! $item->name_banner !!}</span></h3>
            <h4>Tự tin thể hiện phong cách riêng với những mẫu thiết kế độc đáo.</h4>
            <a class="main_btn mt-40" href="{{ route('product') }}">Khám phá ngay</a>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--================End Home Banner Area =================-->
@endforeach
