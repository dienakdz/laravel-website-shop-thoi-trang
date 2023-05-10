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
                            <th></th>
                            <th scope="col">Số lượng</th>
                            <th></th>
                            <th scope="col">Tổng tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!empty($Ordered))
                            @foreach ($Ordered as $ordered)
                                @foreach ($orderedDetails[$ordered->OrderID] as $item)
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
                                        <td></td>
                                        <td>
                                            <div class="product_count">
                                                <label for="qty_{{ $item->ProductID }}">Số lượng:</label>
                                                <input type="text" name="qty" id="sst_{{ $item->ProductID }}"
                                                    value="{{ $item->QuantityOrdered }}" title="Quantity:"
                                                    class="input-text qty" readonly />

                                            </div>
                                        </td>
                                        <td></td>
                                        <td>
                                            <h5 id="total-product-{{ $item->ProductID }}">
                                                {{ number_format($item->Price * $item->QuantityOrdered) }}đ</h5>
                                        </td>
                                    </tr>
                                @endforeach

                                <tr class="out_button_area">
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        <div class="checkout_btn_inner" style="text-align: right;">
                                            @if ($ordered->Status == 1)
                                                <p style="color: brown; font-size: 15px">Đang đợi người bán xác nhận</p>
                                            @elseif($ordered->Status == 2)
                                                <p style="color: blue; font-size: 15px">Đơn hàng đã được xác nhận</p>
                                            @elseif($ordered->Status == 3)
                                                <form action="{{ route('update-status', ['id' => $ordered->OrderID]) }}"
                                                    method="POST">
                                                    @csrf
                                                    <button type="submit" class="main_btn">Đã nhận được hàng</button>
                                                </form>
                                            @else
                                                <p ><b style="color: green; font-size: 25px;">Đã mua</b></p>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<!--================End Cart Area =================-->
@section('javascript')
@endsection
@include('clients.blocks.footer')
