@include('clients.blocks.header')
@include('clients.banner')
<!--================Category Product Area =================-->
@if (session('msg'))
    <script>
        alert("Vui lòng thêm sản phẩm vào giỏ hàng!");
    </script>
@endif
<section class="cat_product_area section_gap">
    <div class="container">
        <div class="row flex-row-reverse">
            <div class="col-lg-9">
                <div class="product_top_bar">
                    <div class="left_dorp">
                        <form action="" method="GET" id="sorting-form">
                            <select class="sorting" name="sorting" id="sorting-select" onchange="this.form.submit()">
                                <option value="0">Sắp xếp theo giá</option>
                                <option value="asc" @if (isset($_GET['sorting']) && $_GET['sorting'] == 'asc') selected @endif>Thấp - Cao
                                </option>
                                <option value="desc" @if (isset($_GET['sorting']) && $_GET['sorting'] == 'desc') selected @endif>Cao - Thấp
                                </option>
                            </select>
                        </form>
                    </div>
                </div>

                <div class="latest_product_inner">
                    <div class="row">
                        @if (count($productList) == 0)
                            <p>KHÔNG CÓ DỮ LIỆU TÌM KIẾM CHO DANH MỤC VÀ THƯƠNG HIỆU NÀY!</p>
                        @endif

                        @foreach ($productList as $item)
                            <div class="col-lg-4 col-md-6">
                                <div class="single-product">
                                    <div class="product-img">
                                        <img class="card-img"
                                            src="{{ asset('assets/admin/img/upload/' . $item->Picture) }}"
                                            alt="{{ $item->ProductName }}" />
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
                                        <a href="{{ route('detail-product', ['id' => $item->ProductID]) }}"
                                            class="d-block">
                                            <h4>{{ $item->ProductName }}</h4>
                                        </a>
                                        <div class="mt-3">
                                            <span class="mr-4">{{ number_format($item->Price) }}đ</span>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
                <div style="text-align: center; margin-top: 20px;margin-bottom: 20px;">
                    {{ $productList->links() }}
                </div>
            </div>

            <div class="col-lg-3">
                <div class="left_sidebar_area">
                    <aside class="left_widgets p_filter_widgets">
                        <div class="l_w_title">
                            <h3>Danh sách danh mục</h3>
                        </div>
                        <div class="widgets_inner">
                            <form action="" method="GET" id="category-form">
                                @if (!empty(getAllCategories()))
                                    @foreach (getAllCategories() as $item)
                                        <input type="radio" name="category" value="{{ $item->CategoryID }}"
                                            id="{{ $item->CategoryName }}"
                                            @if (isset($_GET['category']) && $_GET['category'] == $item->CategoryID) checked @endif>
                                        <label for="{{ $item->CategoryName }}">{{ $item->CategoryName }}</label>
                                        <br>
                                    @endforeach
                                @endif
                            </form>
                        </div>
                    </aside>

                    <aside class="left_widgets p_filter_widgets">
                        <div class="l_w_title">
                            <h3>Danh sách thương hiệu</h3>
                        </div>
                        <div class="widgets_inner">
                            <form action="" method="GET" id="brand-form">
                                @if (!empty(getAllBrands()))
                                    @foreach (getAllBrands() as $item)
                                        <input type="radio" name="brand" value="{{ $item->BrandID }}"
                                            id="{{ $item->BrandName }}"
                                            @if (isset($_GET['brand']) && $_GET['brand'] == $item->BrandID) checked @endif>
                                        <label for="{{ $item->BrandName }}">{{ $item->BrandName }}</label>
                                        <br>
                                    @endforeach
                                @endif
                            </form>
                        </div>
                    </aside>

                    <aside class="left_widgets p_filter_widgets">
                        <div class="l_w_title">
                            <h3>Price Filter</h3>
                        </div>
                        <div class="widgets_inner">
                            <div class="range_item">
                                <div id="slider-range"></div>
                                <div class="">
                                    <form action="" method="GET" id="filter-form">
                                        <label for="amount">Price : </label>
                                        <input type="text" id="amount" name="amount" readonly />
                                        <input type="submit" value="LỌC" />
                                    </form>
                                </div>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </div>
</section>

<!--================End Category Product Area =================-->
@include('clients.blocks.footer')
<script src="http://127.0.0.1:8000/assets/clients/js/slider-range.js"></script>
<script>
    const categoryRadioButtons = document.querySelectorAll('input[name="category"]');
    categoryRadioButtons.forEach(radio => {
        radio.addEventListener('click', () => {
            document.getElementById('category-form').submit(); // Submit category form only
        });
    });

    const brandRadioButtons = document.querySelectorAll('input[name="brand"]');
    brandRadioButtons.forEach(radio => {
        radio.addEventListener('click', () => {
            document.getElementById('brand-form').submit(); // Submit brand form only
        });
    });


    //Xử lý khi kết hợp nhiều chuỗi để lọc
    const categoryForm = document.getElementById("category-form");
    const brandForm = document.getElementById("brand-form");
    const filterForm = document.getElementById("filter-form");
    const filterButton = filterForm.querySelector('input[type="submit"]');

    const sortingForm = document.getElementById('sorting-form');
    const sortingSelect = document.getElementById('sorting-select');


    let isFiltering = false;

    // Gán sự kiện click cho nút "LỌC"
    filterButton.addEventListener("click", function(event) {
        event.preventDefault(); // Ngăn chặn form submit mặc định
        isFiltering = true; // Đặt cờ hiệu để biết rằng đang thực hiện lọc
        updateQueryParams(); // Gọi hàm để cập nhật các tham số truy vấn
    });

    // Gán sự kiện click cho form "Danh mục sản phẩm"
    categoryForm.addEventListener("click", updateQueryParams);

    // Gán sự kiện click cho form "Thương hiệu sản phẩm"
    brandForm.addEventListener("click", updateQueryParams);

    // Gán sự kiện change cho form "Sắp xếp sản phẩm theo giá"
    sortingSelect.addEventListener("change", updateQueryParams);
    // Hàm cập nhật các tham số truy vấn
    function updateQueryParams() {
        let queryParams = [];
        const categoryChecked = categoryForm.querySelector('input[name="category"]:checked');
        const brandChecked = brandForm.querySelector('input[name="brand"]:checked');
        const amountInput = filterForm.querySelector('input[name="amount"]');

        // Thêm tham số truy vấn "category" vào mảng queryParams nếu đã chọn danh mục sản phẩm
        if (categoryChecked) {
            queryParams.push(`category=${categoryChecked.value}`);
        }

        // Thêm tham số truy vấn "brand" vào mảng queryParams nếu đã chọn thương hiệu sản phẩm
        if (brandChecked) {
            queryParams.push(`brand=${brandChecked.value}`);
        }

        //Thêm tham số truy vấn "sorting" vào mảng queryParams nếu đã chọn sắp xếp
        const selectedOption = sortingSelect.options[sortingSelect.selectedIndex];
        const sortingValue = selectedOption.value;
        if (sortingValue !== "0") {
            queryParams.push(`sorting=${sortingValue}`);
        }

        // Nếu đang thực hiện lọc và đã nhập giá tiền, thêm tham số truy vấn "amount" vào mảng queryParams
        if (isFiltering && amountInput) {
            queryParams.push(`amount=${amountInput.value}`);
        }

        // Tạo url mới với các tham số truy vấn được cập nhật
        const url = `/san-pham?${queryParams.join("&")}`;

        // Chuyển hướng trình duyệt đến url mới
        window.location.href = url;
    }
</script>
