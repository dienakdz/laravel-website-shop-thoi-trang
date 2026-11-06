@include('clients.blocks.header')
@include('clients.banner')

<!--================Cart Area =================-->
<section class="cart_area">
    <div class="container">
        <!-- Thông báo Flash message -->
        @if (session('msg_success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="fa fa-check-circle mr-2"></i> {{ session('msg_success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (session('msg_cart_error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i class="fa fa-exclamation-triangle mr-2"></i> {{ session('msg_cart_error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="cart_inner">
            @if (!empty($getAllCarts) && count($getAllCarts) > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Sản phẩm</th>
                                <th scope="col">Giá</th>
                                <th scope="col">Số lượng</th>
                                <th scope="col">Tổng tiền</th>
                                <th scope="col">Xóa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($getAllCarts as $item)
                                <tr id="cart-item-row-{{ $item->CartID }}">
                                    <td>
                                        <div class="media">
                                            <div class="d-flex">
                                                <img class="img-carts"
                                                    src="{{ asset('assets/admin/img/upload/' . $item->Picture) }}"
                                                    alt="{{ $item->ProductName }}" />
                                            </div>
                                            <div class="media-body">
                                                <a href="{{ route('detail-product', ['id' => $item->ProductID]) }}"
                                                    style="color: inherit; text-decoration: none;">
                                                    <p class="font-weight-bold">{{ $item->ProductName }}</p>
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <h5>{{ number_format($item->Price) }}₫</h5>
                                    </td>
                                    <td>
                                        <div class="product_count">
                                            <form action="{{ route('update-carts') }}" method="POST"
                                                id="update-cart-form-{{ $item->ProductID }}">
                                                @csrf
                                                <label for="sst_{{ $item->ProductID }}">Số lượng:</label>
                                                <input type="hidden" name="productID" value="{{ $item->ProductID }}">
                                                <input type="text" name="qty" id="sst_{{ $item->ProductID }}"
                                                    maxlength="{{ $item->Quantity }}" value="{{ $item->CartQuantity }}"
                                                    title="Quantity:" class="input-text qty" readonly />
                                                <button type="button"
                                                    onclick="increaseQty({{ $item->ProductID }},{{ $item->Quantity }})"
                                                    class="increase items-count"
                                                    data-product-id="{{ $item->ProductID }}"><i
                                                        class="lnr lnr-chevron-up"></i></button>
                                                <button type="button" onclick="reducedQty({{ $item->ProductID }})"
                                                    class="reduced items-count"
                                                    data-product-id="{{ $item->ProductID }}"><i
                                                        class="lnr lnr-chevron-down"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                    <td>
                                        <h5 id="total-product-{{ $item->ProductID }}">
                                            {{ number_format($item->Price * $item->CartQuantity) }}₫
                                        </h5>
                                    </td>
                                    <td>
                                        <form action="{{ route('delete-product-cart', ['id' => $item->CartID]) }}"
                                            method="post" class="delete-form">
                                            @csrf
                                            <button type="button" data-price="{{ $item->Price }}"
                                                data-quantity="{{ $item->CartQuantity }}" class="delete-item-btn"
                                                title="Xóa sản phẩm"
                                                style="color: #dc3545; font-size: 20px; background-color: transparent; border: none; cursor: pointer;">
                                                <i class="ti-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td></td>
                                <td></td>
                                <td>
                                    <h5 class="font-weight-bold text-uppercase">Tổng tiền:</h5>
                                </td>
                                <td>
                                    <h5 id="total-price" class="text-danger font-weight-bold" style="font-size: 18px;">
                                        {{ number_format($totalPrice) }}₫
                                    </h5>
                                </td>
                                <td></td>
                            </tr>
                            <tr class="out_button_area">
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>
                                    <div class="checkout_btn_inner">
                                        <a class="gray_btn" href="{{ route('product') }}">Tiếp tục mua hàng</a>
                                        <a class="main_btn" href="{{ route('checkout') }}">Tiến hành đặt hàng</a>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @else
                <!-- Giao diện khi giỏ hàng trống (Empty Cart) -->
                <div class="order_empty_box text-center py-5">
                    <div class="mb-3" style="font-size: 50px; color: #a0aec0;">
                        <i class="ti-shopping-cart"></i>
                    </div>
                    <h4>Giỏ hàng của bạn đang trống</h4>
                    <p class="text-muted">Chưa có sản phẩm nào trong giỏ hàng. Hãy dạo quanh cửa hàng và chọn những bộ trang phục ưng ý nhé!</p>
                    <a class="main_btn" href="{{ route('product') }}">Khám phá sản phẩm ngay</a>
                </div>
            @endif
        </div>
    </div>
</section>
<!--================End Cart Area =================-->

@section('javascript')
    <script>
        $(function() {
            $('.delete-item-btn').click(function(e) {
                e.preventDefault();
                if (!confirm('Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?')) {
                    return;
                }

                var btn = $(this);
                var form = btn.closest('form');
                var url = form.attr('action');

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: form.serialize(),
                    success: function() {
                        // Reload trang để cập nhật lại tổng tiền, giỏ hàng trống và badge số lượng ở header
                        location.reload();
                    },
                    error: function() {
                        alert('Có lỗi xảy ra khi xóa sản phẩm!');
                    }
                });
            });
        });

        function increaseQty(productID, Quantity) {
            var result = document.getElementById(('sst_' + productID));
            var sst = parseInt(result.value);
            if (!isNaN(sst) && sst < Quantity) {
                result.value = sst + 1;
                updateCartAjax(productID, result.value);
            } else {
                alert('Sản phẩm chỉ còn tối đa ' + Quantity + ' món trong kho!');
            }
        }

        function reducedQty(productID) {
            var result = document.getElementById('sst_' + productID);
            var sst = parseInt(result.value);
            if (!isNaN(sst) && sst > 1) {
                result.value = sst - 1;
                updateCartAjax(productID, result.value);
            }
        }

        function updateCartAjax(productID, qty) {
            $.ajax({
                url: "{{ route('update-carts') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    productID: productID,
                    qty: qty
                },
                success: function(response) {
                    var totalPrice = response.totalPrice;
                    var totalPriceEl = document.getElementById('total-price');
                    if (totalPriceEl) {
                        totalPriceEl.innerHTML = totalPrice.toLocaleString('vi-VN') + '₫';
                    }

                    var totalProduct = response.totalProduct;
                    var totalProductEl = document.getElementById('total-product-' + productID);
                    if (totalProductEl) {
                        totalProductEl.innerHTML = totalProduct.toLocaleString('vi-VN') + '₫';
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown);
                }
            });
        }
    </script>
@endsection

@include('clients.blocks.footer')
