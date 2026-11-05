@include('clients.blocks.header')
@include('clients.banner')

<!--================ Orders Section =================-->
<section class="order_history_area">
    <div class="container">
        <!-- Thông báo Flash message -->
        @if (session('msg_status_success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('msg_status_success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Tabs lọc trạng thái đơn hàng (Phong cách tối giản) -->
        <ul class="order_nav_tabs">
            <li class="nav_item">
                <a href="{{ route('ordered', ['status' => 'all']) }}"
                    class="nav_link {{ $currentStatus === 'all' ? 'active' : '' }}">
                    Tất cả <span class="count">({{ $counts['all'] ?? 0 }})</span>
                </a>
            </li>
            <li class="nav_item">
                <a href="{{ route('ordered', ['status' => '1']) }}"
                    class="nav_link {{ $currentStatus === '1' ? 'active' : '' }}">
                    Chờ xác nhận <span class="count">({{ $counts['1'] ?? 0 }})</span>
                </a>
            </li>
            <li class="nav_item">
                <a href="{{ route('ordered', ['status' => '2']) }}"
                    class="nav_link {{ $currentStatus === '2' ? 'active' : '' }}">
                    Đang chuẩn bị <span class="count">({{ $counts['2'] ?? 0 }})</span>
                </a>
            </li>
            <li class="nav_item">
                <a href="{{ route('ordered', ['status' => '3']) }}"
                    class="nav_link {{ $currentStatus === '3' ? 'active' : '' }}">
                    Đang giao hàng <span class="count">({{ $counts['3'] ?? 0 }})</span>
                </a>
            </li>
            <li class="nav_item">
                <a href="{{ route('ordered', ['status' => '4']) }}"
                    class="nav_link {{ $currentStatus === '4' ? 'active' : '' }}">
                    Hoàn thành <span class="count">({{ $counts['4'] ?? 0 }})</span>
                </a>
            </li>
            <li class="nav_item">
                <a href="{{ route('ordered', ['status' => '0']) }}"
                    class="nav_link {{ $currentStatus === '0' ? 'active' : '' }}">
                    Đã hủy <span class="count">({{ $counts['0'] ?? 0 }})</span>
                </a>
            </li>
        </ul>

        <!-- Danh sách đơn hàng -->
        @if (!empty($Ordered) && count($Ordered) > 0)
            <div class="orders_wrap">
                @foreach ($Ordered as $ordered)
                    <div class="order_block">
                        <!-- Header đơn hàng -->
                        <div class="order_block_header">
                            <div>
                                <span class="order_id">ĐƠN HÀNG #ODR-{{ str_pad($ordered->OrderID, 5, '0', STR_PAD_LEFT) }}</span>
                                <span class="order_date">
                                    {{ !empty($ordered->OrderDate) ? date('d/m/Y - H:i', strtotime($ordered->OrderDate)) : '' }}
                                </span>
                            </div>

                            <div>
                                @if ($ordered->Status == 1)
                                    <span class="order_status_tag st-1">Chờ xác nhận</span>
                                @elseif($ordered->Status == 2)
                                    <span class="order_status_tag st-2">Đang chuẩn bị hàng</span>
                                @elseif($ordered->Status == 3)
                                    <span class="order_status_tag st-3">Đang vận chuyển</span>
                                @elseif($ordered->Status == 4)
                                    <span class="order_status_tag st-4">Giao thành công</span>
                                @elseif($ordered->Status == 0)
                                    <span class="order_status_tag st-0">Đã hủy</span>
                                @else
                                    <span class="order_status_tag st-2">Đang xử lý</span>
                                @endif
                            </div>
                        </div>

                        <!-- Danh sách sản phẩm trong đơn -->
                        <table class="order_items_table">
                            <tbody>
                                @if (!empty($orderedDetails[$ordered->OrderID]) && count($orderedDetails[$ordered->OrderID]) > 0)
                                    @foreach ($orderedDetails[$ordered->OrderID] as $item)
                                        <tr>
                                            <td style="width: 75px;">
                                                <img class="order_product_thumb"
                                                    src="{{ asset('assets/admin/img/upload/' . $item->Picture) }}"
                                                    alt="{{ $item->ProductName }}" />
                                            </td>
                                            <td>
                                                <a href="{{ route('detail-product', ['id' => $item->ProductID]) }}"
                                                    class="order_product_title">
                                                    {{ $item->ProductName }}
                                                </a>
                                                <p class="order_product_meta">
                                                    Đơn giá: {{ number_format($item->Price) }}₫ &nbsp;|&nbsp; Số lượng: x{{ $item->QuantityOrdered }}
                                                </p>
                                            </td>
                                            <td class="order_item_subtotal">
                                                {{ number_format($item->Price * $item->QuantityOrdered) }}₫
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="3" class="text-muted py-3">
                                            Không có chi tiết sản phẩm cho đơn hàng này.
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>

                        <!-- Chân đơn hàng -->
                        <div class="order_block_footer">
                            <div class="order_delivery_info">
                                <p><strong>Giao đến:</strong> {{ $ordered->Fulname ?? 'Khách hàng' }} - {{ $ordered->Phonenumber ?? 'N/A' }}</p>
                                <p><strong>Địa chỉ:</strong> {{ $ordered->Address ?? 'Chưa cập nhật' }}</p>
                                <p><strong>Thanh toán:</strong> {{ $ordered->Shippingtype ?? 'Thanh toán khi nhận hàng (COD)' }}</p>
                            </div>

                            <div class="order_footer_right">
                                <div class="order_final_price">
                                    Tổng thanh toán: <strong>{{ number_format($ordered->Totalprice) }}₫</strong>
                                </div>

                                <div>
                                    @if ($ordered->Status == 1)
                                        <form action="{{ route('cancel-order', ['id' => $ordered->OrderID]) }}"
                                            method="POST" class="d-inline"
                                            onsubmit="return confirm('Bạn có chắc muốn hủy đơn hàng này không?');">
                                            @csrf
                                            <button type="submit" class="order_btn_secondary">
                                                Hủy đơn hàng
                                            </button>
                                        </form>
                                    @elseif($ordered->Status == 3)
                                        <form action="{{ route('update-status', ['id' => $ordered->OrderID]) }}"
                                            method="POST" class="d-inline"
                                            onsubmit="return confirm('Bạn xác nhận đã nhận được kiện hàng này?');">
                                            @csrf
                                            <button type="submit" class="order_btn_primary">
                                                Đã nhận được hàng
                                            </button>
                                        </form>
                                    @elseif($ordered->Status == 4)
                                        <a href="{{ route('product') }}" class="order_btn_secondary">
                                            Mua lại
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Trạng thái trống (Empty Box) -->
            <div class="order_empty_box text-center">
                <h4>Chưa có đơn hàng nào</h4>
                <p>
                    @if ($currentStatus === 'all')
                        Bạn chưa có đơn mua nào tại cửa hàng.
                    @else
                        Hiện không có đơn hàng nào trong mục này.
                    @endif
                </p>
                <a href="{{ route('product') }}" class="order_btn_primary">
                    Tiếp tục mua sắm
                </a>
            </div>
        @endif
    </div>
</section>
<!--================ End Orders Section =================-->

@include('clients.blocks.footer')
