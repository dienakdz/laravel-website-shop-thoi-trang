<!--================Home Banner Area =================-->
@php
  $heroBanner = is_iterable($banner) ? (collect($banner)->firstWhere('is_active', 1) ?? collect($banner)->first()) : $banner;
@endphp
@if($heroBanner)
  @php
    $bannerImg = $heroBanner->image_banner;
    $bannerUrl = asset('assets/admin/img/' . $bannerImg);
    if (!empty($bannerImg) && file_exists(public_path('assets/admin/img/upload/' . $bannerImg))) {
        $bannerUrl = asset('assets/admin/img/upload/' . $bannerImg);
    }
  @endphp
  <section class="home_banner_area mb-40" style="background-image: url('{{ $bannerUrl }}')">
    <div class="banner_inner d-flex align-items-center">
      <div class="container">
        <div class="banner_content row">
          <div class="col-lg-12">
            <p class="sub text-uppercase">Thời trang xu hướng mới</p>
            <h3><span>{!! $heroBanner->name_banner !!}</span></h3>
            <h4>{{ !empty($heroBanner->content_banner) ? $heroBanner->content_banner : 'Tự tin thể hiện phong cách riêng với những mẫu thiết kế độc đáo.' }}</h4>
            <a class="main_btn mt-40" href="{{ route('product') }}">Khám phá ngay</a>
          </div>
        </div>
      </div>
    </div>
  </section>
@endif
<!--================End Home Banner Area =================-->
