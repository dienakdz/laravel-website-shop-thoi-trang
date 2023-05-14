@include('clients.blocks.header')
@include('clients.banner')

<div class="container">
    <section class="about">
        <div class="row main">
            <div class="col-lg-5">
                <img src="{{ asset('assets/clients/img/about-me.jpg') }}" alt="">
            </div>
            <div class="col-lg-6">
                <div class="about-text">
                    <h1>Minh Diện</h1>
                    <h5>PHP <span>& Laravel Developer</span></h5>
                    <p>
                        Tôi cam kết mang đến cho bạn những sản phẩm và dịch vụ chất lượng nhất, với mức giá hợp lý và sự
                        hỗ trợ tận tình nhất. Tôi luôn cập nhật và nâng cao chất lượng sản phẩm của mình để đáp
                        ứng mọi nhu cầu và yêu cầu của khách hàng 
                        <span><i class="fa fa-heart"></i></span>
                    </p>
                    <button>
                        <a href="http://www.fb.com/dienakdz">Tìm hiểu thêm </a>
                    </button>
                </div>
            </div>
        </div>
    </section>
</div>

@section('javascript')
@endsection
@include('clients.blocks.footer')
