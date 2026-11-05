@include('clients.blocks.header')
@include('clients.banner')

<!--================Category Product Area =================-->
<section class="cat_product_area section_gap py-5">
    <div class="container">
        <div class="row flex-row-reverse">
            <!-- Khu vực danh sách sản phẩm -->
            <div class="col-lg-9">
                <!-- Thanh công cụ sắp xếp & thông tin -->
                <div class="product_top_bar p-3 mb-4 bg-white border rounded shadow-sm d-flex justify-content-between align-items-center">
                    <div class="left_dorp">
                        <form action="" method="GET" id="sorting-form" class="form-inline m-0">
                            @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                            @if(request('brand')) <input type="hidden" name="brand" value="{{ request('brand') }}"> @endif
                            @if(request('amount')) <input type="hidden" name="amount" value="{{ request('amount') }}"> @endif

                            <label class="mr-2 text-muted small font-weight-bold mb-0">Sắp xếp:</label>
                            <select class="form-control form-control-sm font-weight-bold" name="sorting" id="sorting-select" onchange="this.form.submit()">
                                <option value="0">Mặc định (Ngẫu nhiên)</option>
                                <option value="asc" @if (request('sorting') == 'asc') selected @endif>Giá: Thấp đến Cao</option>
                                <option value="desc" @if (request('sorting') == 'desc') selected @endif>Giá: Cao đến Thấp</option>
                            </select>
                        </form>
                    </div>

                    @if(request('category') || request('brand') || request('sorting') || request('amount'))
                        <div>
                            <a href="{{ route('product') }}" class="btn btn-outline-danger btn-sm">
                                <i class="fa fa-times-circle mr-1"></i> Xóa bộ lọc
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Lưới hiển thị sản phẩm -->
                <div class="latest_product_inner">
                    <div class="row">
                        @forelse ($itemSearch ?? $productList as $item)
                            <div class="col-lg-4 col-md-6 mb-4 d-flex">
                                <div class="single-product w-100">
                                    <div class="product-img">
                                        @if(isset($item->ProductStatusID))
                                            @if($item->ProductStatusID == 2)
                                                <span class="product-badge hot-badge"><i class="fa fa-fire"></i> Hot</span>
                                            @elseif($item->ProductStatusID == 1)
                                                <span class="product-badge new-badge">Mới</span>
                                            @elseif($item->ProductStatusID == 3)
                                                <span class="product-badge sale-badge">Giảm giá</span>
                                            @endif
                                        @endif
                                        <img class="img-fluid"
                                            src="{{ asset('assets/admin/img/upload/' . $item->Picture) }}"
                                            alt="{{ $item->ProductName }}" />
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
                        @empty
                            <div class="col-12 text-center py-5">
                                <i class="ti-package text-muted mb-3" style="font-size: 48px; display: block;"></i>
                                <h5 class="text-muted font-weight-bold">Không tìm thấy sản phẩm nào phù hợp!</h5>
                                <p class="text-muted small">Hãy thử chọn danh mục khác hoặc xóa bộ lọc để xem toàn bộ sản phẩm.</p>
                                <a href="{{ route('product') }}" class="btn btn-success btn-sm mt-2">Xem tất cả sản phẩm</a>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Phân trang -->
                <div class="d-flex justify-content-center my-4">
                    @if (!empty($itemSearch))
                        {{ $itemSearch->withQueryString()->links() }}
                    @else
                        {{ $productList->withQueryString()->links() }}
                    @endif
                </div>
            </div>

            <!-- Sidebar bộ lọc bên trái -->
            <div class="col-lg-3">
                <div class="left_sidebar_area">
                    <!-- Lọc theo danh mục -->
                    <aside class="left_widgets p_filter_widgets mb-4 bg-white border rounded shadow-sm p-3">
                        <div class="l_w_title border-bottom pb-2 mb-3">
                            <h5 class="font-weight-bold m-0" style="color: #2c3e50;">Danh mục sản phẩm</h5>
                        </div>
                        <div class="widgets_inner">
                            <ul class="list-unstyled m-0">
                                <li class="mb-2">
                                    <a href="{{ route('product', array_merge(request()->except(['category', 'page']))) }}"
                                       class="d-flex justify-content-between align-items-center {{ !request('category') ? 'text-success font-weight-bold' : 'text-dark' }}">
                                        <span><i class="fa fa-angle-right mr-2"></i> Tất cả danh mục</span>
                                    </a>
                                </li>
                                @if (!empty(getAllCategories()))
                                    @foreach (getAllCategories() as $item)
                                        <li class="mb-2">
                                            <a href="{{ route('product', array_merge(request()->except(['category', 'page']), ['category' => $item->CategoryID])) }}"
                                               class="d-flex justify-content-between align-items-center {{ request('category') == $item->CategoryID ? 'text-success font-weight-bold' : 'text-dark' }}">
                                                <span><i class="fa fa-angle-right mr-2"></i> {{ $item->CategoryName }}</span>
                                                @if(request('category') == $item->CategoryID)
                                                    <i class="fa fa-check text-success small"></i>
                                                @endif
                                            </a>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </aside>

                    <!-- Lọc theo thương hiệu -->
                    <aside class="left_widgets p_filter_widgets mb-4 bg-white border rounded shadow-sm p-3">
                        <div class="l_w_title border-bottom pb-2 mb-3">
                            <h5 class="font-weight-bold m-0" style="color: #2c3e50;">Thương hiệu</h5>
                        </div>
                        <div class="widgets_inner">
                            <ul class="list-unstyled m-0">
                                <li class="mb-2">
                                    <a href="{{ route('product', array_merge(request()->except(['brand', 'page']))) }}"
                                       class="d-flex justify-content-between align-items-center {{ !request('brand') ? 'text-success font-weight-bold' : 'text-dark' }}">
                                        <span><i class="fa fa-angle-right mr-2"></i> Tất cả thương hiệu</span>
                                    </a>
                                </li>
                                @if (!empty(getAllBrands()))
                                    @foreach (getAllBrands() as $item)
                                        <li class="mb-2">
                                            <a href="{{ route('product', array_merge(request()->except(['brand', 'page']), ['brand' => $item->BrandID])) }}"
                                               class="d-flex justify-content-between align-items-center {{ request('brand') == $item->BrandID ? 'text-success font-weight-bold' : 'text-dark' }}">
                                                <span><i class="fa fa-angle-right mr-2"></i> {{ $item->BrandName }}</span>
                                                @if(request('brand') == $item->BrandID)
                                                    <i class="fa fa-check text-success small"></i>
                                                @endif
                                            </a>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </aside>

                    <!-- Lọc theo khoảng giá -->
                    <aside class="left_widgets p_filter_widgets mb-4 bg-white border rounded shadow-sm p-3">
                        <div class="l_w_title border-bottom pb-2 mb-3">
                            <h5 class="font-weight-bold m-0" style="color: #2c3e50;">Lọc theo giá</h5>
                        </div>
                        <div class="widgets_inner">
                            <ul class="list-unstyled m-0">
                                <li class="mb-2">
                                    <a href="{{ route('product', array_merge(request()->except(['amount', 'page']))) }}"
                                       class="{{ !request('amount') ? 'text-success font-weight-bold' : 'text-dark' }}">
                                        <i class="fa fa-circle-o mr-2"></i> Tất cả mức giá
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="{{ route('product', array_merge(request()->except(['amount', 'page']), ['amount' => '0 - 150.000'])) }}"
                                       class="{{ request('amount') == '0 - 150.000' ? 'text-success font-weight-bold' : 'text-dark' }}">
                                        <i class="fa fa-circle-o mr-2"></i> Dưới 150.000đ
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="{{ route('product', array_merge(request()->except(['amount', 'page']), ['amount' => '150.000 - 300.000'])) }}"
                                       class="{{ request('amount') == '150.000 - 300.000' ? 'text-success font-weight-bold' : 'text-dark' }}">
                                        <i class="fa fa-circle-o mr-2"></i> 150.000đ - 300.000đ
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="{{ route('product', array_merge(request()->except(['amount', 'page']), ['amount' => '300.000 - 600.000'])) }}"
                                       class="{{ request('amount') == '300.000 - 600.000' ? 'text-success font-weight-bold' : 'text-dark' }}">
                                        <i class="fa fa-circle-o mr-2"></i> 300.000đ - 600.000đ
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="{{ route('product', array_merge(request()->except(['amount', 'page']), ['amount' => '600.000 - 2.000.000'])) }}"
                                       class="{{ request('amount') == '600.000 - 2.000.000' ? 'text-success font-weight-bold' : 'text-dark' }}">
                                        <i class="fa fa-circle-o mr-2"></i> Trên 600.000đ
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </div>
</section>
<!--================End Category Product Area =================-->

@include('clients.blocks.footer')
