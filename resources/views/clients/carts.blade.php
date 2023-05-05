@include('clients.blocks.header')
@include('clients.banner')

<!--================Cart Area =================-->
<section class="cart_area">
    <div class="container">
        <div class="cart_inner">
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
                        @if (!empty($getAllCarts))
                            @foreach ($getAllCarts as $item)
                                <tr>
                                    <td>
                                        <div class="media">
                                            <div class="d-flex">
                                                <img class="img-carts"
                                                    src="{{ asset('assets/admin/img/upload/' . $item->Picture) }}"
                                                    alt="" />
                                            </div>
                                            <div class="media-body">
                                                <a href="{{ route('detail-product', ['id' => $item->ProductID]) }}"
                                                    style="color: inherit;
                                                    text-decoration: none;">
                                                    <p>{{ $item->ProductName }}</p>
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <h5>{{ number_format($item->Price) }}</h5>
                                    </td>
                                    <td>
                                        <div class="product_count">
                                            <form action="{{ route('update-carts') }}" method="POST"
                                                id="update-cart-form">
                                                @csrf
                                                <label for="qty_{{ $item->ProductID }}">Số lượng:</label>
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
                                            {{ number_format($item->Price * $item->CartQuantity) }}</h5>
                                    </td>
                                    <td>
                                        <form action="{{ route('delete-product-cart', ['id' => $item->CartID]) }}"
                                            method="post" class="delete-form">
                                            @csrf
                                            <button type="button" data-price="{{ $item->Price }}"
                                                data-quantity="{{ $item->CartQuantity }}" class="delete-item-btn"
                                                style="color:green; font-size: 30px; background-color: transparent; border: none;">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        <tr>
                            <td></td>
                            <td></td>
                            <td>
                                <h5>Tổng tiền </h5>
                            </td>
                            <td>
                                <h5 id="total-price">{{ number_format($totalPrice) }}</h5>
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
                                    <a class="main_btn" href="{{ route('checkout') }}">Đặt hàng</a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<!--================End Cart Area =================-->
@section('javascript')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(function() {
            $('.delete-item-btn').click(function(e) {
                e.preventDefault();
                var btn = $(this);
                var form = btn.closest('form');
                var url = form.attr('action');
                var price = parseFloat(btn.data('price'));
                var quantity = parseFloat(btn.data('quantity'));
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: form.serialize(),
                    success: function() {
                        form.closest('tr').remove(); // Xóa sản phẩm đã chọn khỏi bảng
                        var totalPrice = {{ $totalPrice }};
                        var newTotalPrice = totalPrice - (price *
                            quantity); // Cập nhật tổng giá tiền
                        $('#total-price').text(newTotalPrice.toLocaleString('vi-VN') + ' VND');
                    },
                    error: function() {
                        // Xử lý lỗi nếu có
                    }
                });
            });
        });

        function increaseQty(productID, Quantity) {
            var result = document.getElementById(('sst_' + productID));
            var sst = parseInt(result.value);
            if (!isNaN(sst) && sst < Quantity) {
                result.value = sst + 1;
                $.ajax({
                    url: "{{ route('update-carts') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        productID: productID,
                        qty: result.value
                    },
                    success: function(response) {
                        //Xử lý tổng tiền tất cả sản phẩm trả về 
                        var totalPrice = response.totalPrice;
                        // Tiến hành thay đổi tổng tiền tương ứng với sản phẩm cập nhật
                        var totalPriceEl = document.getElementById('total-price');
                        console.log(totalPrice);
                        if (totalPriceEl) {
                            totalPriceEl.innerHTML = totalPrice.toLocaleString('vi-VN', {
                                style: 'currency',
                                currency: 'VND'
                            });
                        } //Xử lý tổng tiền 1 sản phẩm trả về var
                        totalProduct = response
                            .totalProduct; // Tiến hành thay đổi tổng tiền 1 sản phẩm cập nhật var
                        totalProductl = document.getElementById('total-product-' + productID);
                        console.log(totalProduct);
                        if (totalProductl) {
                            totalProductl.innerHTML = totalProduct.toLocaleString('vi-VN', {
                                style: 'currency',
                                currency: 'VND'
                            });
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(errorThrown);
                    }
                });
            }
        }

        function reducedQty(productID) {
            var result = document.getElementById('sst_' + productID);
            var sst = parseInt(result.value);
            if (!isNaN(sst) && sst > 1) {
                result.value = sst - 1;

                $.ajax({
                    url: "{{ route('update-carts') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        productID: productID,
                        qty: result.value
                    },
                    success: function(response) {
                        //Xử lý tổng tiền tất cả sản phẩm trả về
                        var totalPrice = response.totalPrice;
                        // Tiến hành thay đổi tổng tiền tương ứng với sản phẩm cập nhật
                        var totalPriceEl = document.getElementById('total-price');
                        console.log(totalPrice);
                        if (totalPriceEl) {
                            totalPriceEl.innerHTML = totalPrice.toLocaleString('vi-VN', {
                                style: 'currency',
                                currency: 'VND'
                            });
                        }

                        //Xử lý tổng tiền 1 sản phẩm trả về
                        var totalProduct = response.totalProduct;
                        // Tiến hành thay đổi tổng tiền 1 sản phẩm cập nhật
                        var totalProductl = document.getElementById('total-product-' + productID);
                        console.log(totalProduct);
                        if (totalProductl) {
                            totalProductl.innerHTML = totalProduct.toLocaleString('vi-VN', {
                                style: 'currency',
                                currency: 'VND'
                            });
                        }

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(errorThrown);
                    }
                });
            }
        }
    </script>
@endsection
@include('clients.blocks.footer')
