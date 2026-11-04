<!--================ start footer Area  =================-->
<footer class="footer-area">
    <div class="container">
        <div class="row">
            <!-- Cột 1: Giới thiệu Shop -->
            <div class="col-lg-4 col-md-6 single-footer-widget mb-4 mb-lg-0">
                <h4>Về Shop Thời Trang</h4>
                <p>
                    Chúng tôi mang đến những bộ sưu tập thời trang hiện đại, phong cách và chất lượng tốt nhất với mức giá hợp lý. Tự tin khẳng định gu thời trang riêng của bạn mỗi ngày.
                </p>
                <p><i class="fa fa-map-marker mr-2 text-success"></i> <strong>Địa chỉ:</strong> 470 Trần Đại Nghĩa, Q. Ngũ Hành Sơn, TP. Đà Nẵng</p>
                <p><i class="fa fa-phone mr-2 text-success"></i> <strong>Hotline:</strong> 0967.468.703 (8h00 - 21h30)</p>
                <p><i class="fa fa-envelope mr-2 text-success"></i> <strong>Email:</strong> support@shopthoitrang.vn</p>
            </div>

            <!-- Cột 2: Danh mục sản phẩm -->
            <div class="col-lg-2 col-md-6 single-footer-widget mb-4 mb-lg-0">
                <h4>Danh Mục</h4>
                <ul>
                    <li><a href="{{ route('product') }}"><i class="fa fa-angle-right mr-1"></i> Tất cả sản phẩm</a></li>
                    @php
                        $footerCats = function_exists('getAllCategories') ? getAllCategories() : collect();
                    @endphp
                    @foreach ($footerCats->take(5) as $cat)
                        <li>
                            <a href="{{ route('product', ['category' => $cat->CategoryID]) }}">
                                <i class="fa fa-angle-right mr-1"></i> {{ $cat->CategoryName }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Cột 3: Hỗ trợ & Chính sách -->
            <div class="col-lg-3 col-md-6 single-footer-widget mb-4 mb-lg-0">
                <h4>Hỗ Trợ & Chính Sách</h4>
                <ul>
                    <li><a href="{{ route('about') }}"><i class="fa fa-angle-right mr-1"></i> Giới thiệu thương hiệu</a></li>
                    <li><a href="{{ route('ordered') }}"><i class="fa fa-angle-right mr-1"></i> Tra cứu đơn hàng</a></li>
                    <li><a href="{{ route('contact') }}"><i class="fa fa-angle-right mr-1"></i> Liên hệ & Góp ý</a></li>
                    <li><a href="#"><i class="fa fa-angle-right mr-1"></i> Chính sách đổi trả 7 ngày</a></li>
                    <li><a href="#"><i class="fa fa-angle-right mr-1"></i> Hướng dẫn chọn size</a></li>
                </ul>
            </div>

            <!-- Cột 4: Đăng ký nhận tin & Mạng xã hội -->
            <div class="col-lg-3 col-md-6 single-footer-widget">
                <h4>Nhận Ưu Đãi Mới</h4>
                <p>Đăng ký nhận thông tin khuyến mãi sớm nhất và voucher giảm 10% cho đơn hàng đầu tiên.</p>
                <div class="footer-newsletter-box mb-3">
                    <form action="#" method="GET" onsubmit="event.preventDefault(); alert('Cảm ơn bạn đã đăng ký nhận tin ưu đãi!');" class="d-flex">
                        <input class="form-control" placeholder="Nhập email của bạn..." required type="email">
                        <button class="btn-subscribe" type="submit"><i class="fa fa-paper-plane"></i></button>
                    </form>
                </div>
                <div class="payment-methods mt-3">
                    <p class="mb-1"><small>Chấp nhận thanh toán:</small></p>
                    <span class="badge badge-light p-2 mr-1"><i class="fa fa-money"></i> Tiền mặt (COD)</span>
                    <span class="badge badge-light p-2 mr-1"><i class="fa fa-credit-card"></i> ATM / Visa</span>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom row align-items-center">
            <p class="footer-text m-0 col-lg-8 col-md-12">
                Bản quyền &copy; {{ date('Y') }} <strong>Shop Thời Trang</strong>. Thiết kế và phát triển với niềm đam mê.
            </p>
            <div class="col-lg-4 col-md-12 footer-social text-lg-right text-left mt-3 mt-lg-0">
                <a href="#" title="Facebook"><i class="fa fa-facebook"></i></a>
                <a href="#" title="Instagram"><i class="fa fa-instagram"></i></a>
                <a href="#" title="Youtube"><i class="fa fa-youtube-play"></i></a>
            </div>
        </div>
    </div>
</footer>
<!--================ End footer Area  =================-->

<!-- Optional JavaScript -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('assets/clients/js/popper.js') }}"></script>
<script src="{{ asset('assets/clients/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/clients/js/stellar.js') }}"></script>
<script src="{{ asset('assets/clients/vendors/lightbox/simpleLightbox.min.js') }}"></script>
<script src="{{ asset('assets/clients/vendors/isotope/imagesloaded.pkgd.min.js') }}"></script>
<script src="{{ asset('assets/clients/vendors/isotope/isotope-min.js') }}"></script>
<script src="{{ asset('assets/clients/vendors/owl-carousel/owl.carousel.min.js') }}"></script>
<script src="{{ asset('assets/clients/js/jquery.ajaxchimp.min.js') }}"></script>
<script src="{{ asset('assets/clients/vendors/counter-up/jquery.waypoints.min.js') }}"></script>
<script src="{{ asset('assets/clients/vendors/counter-up/jquery.counterup.js') }}"></script>
<script src="{{ asset('assets/clients/js/mail-script.js') }}"></script>
<script src="{{ asset('assets/clients/vendors/jquery-ui/jquery-ui.js') }}"></script>
<script src="{{ asset('assets/clients/js/voice-search.js') }}"></script>

@yield('javascript')

</body>

</html>
