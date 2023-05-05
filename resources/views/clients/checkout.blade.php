@include('clients.blocks.header')
@include('clients.banner')
<!--================Checkout Area =================-->

<section class="checkout_area section_gap">
    <div class="container">
        <div class="billing_details">
            <div class="row">
                <div class="col-lg-8">
                    <h3>Chi tiết thanh toán</h3>
                    <form class="row contact_form" action="" method="POST" id="form-checkout">
                        @csrf
                        <div class="col-md-6 form-group p_star">
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Họ và tên *" />

                        </div>
                        <div class="col-md-6 form-group p_star">
                            <input type="text" class="form-control" id="sdt" name="sdt"
                                placeholder="Số điện thoại *" />

                        </div>

                        <div class="col-md-4 form-group p_star">
                            <select class="form-select form-select-sm mb-3" id="city" name="tinhthanhpho">
                                <option value="" selected>Chọn tỉnh thành</option>
                            </select>
                        </div>
                        <div class="col-md-4 form-group p_star">
                            <select class="form-select form-select-sm mb-3" id="district" name="quanhuyen">
                                <option value="" selected>Chọn quận huyện</option>
                            </select>
                        </div>
                        <div class="col-md-4 form-group p_star">
                            <select class="form-select form-select-sm" id="ward" name="xaphuong">
                                <option value="" selected>Chọn phường xã</option>
                            </select>
                        </div>

                        <div class="col-md-12 form-group p_star">
                            <textarea class="form-control" name="address" id="address" rows="1" placeholder="Ví dụ: 470 Trần Đại Nghĩa"></textarea>
                        </div>
                </div>
                <div class="col-lg-4">
                    <div class="order_box">
                        <h2>Đơn đặt hàng của bạn</h2>
                        <ul class="list">
                            <li>
                                <a href="#">Sản phẩm
                                    <span>Tổng tiền</span>
                                </a>
                            </li>
                            @foreach ($productCheckOut as $item)
                                <li>
                                    <a href="#">{{ $item->ProductName }}
                                        <input type="hidden" value="{{ $item->ProductID }}" name="productID[]">
                                        <span class="middle">x {{ $item->CartQuantity }}</span>
                                        <span
                                            class="last">{{ number_format($item->Price * $item->CartQuantity) }}</span>
                                    </a>
                                </li>
                            @endforeach



                        </ul>
                        <ul class="list list_2">
                            <li>
                                <a href="#">Tổng tiền sản phẩm
                                    <span>{{ number_format($totalPrice) }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">Shipping
                                    <span>30,000</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">Tổng tiền
                                    <span>{{ number_format($totalPrice + 30000) }}</span>
                                    <input type="hidden" name="totalPrice" value="{{ $totalPrice + 30000 }}">
                                </a>
                            </li>
                        </ul>
                        <div class="payment_item">
                            <div class="radion_btn">
                                <input type="radio" id="f-option5" name="thanhtoan" value="Thanh toán khi nhận hàng"
                                    required />
                                <label for="f-option5">Thanh toán khi nhận hàng</label>
                                <div class="check"></div>
                            </div>
                        </div>
                        <div class="payment_item">
                            <div class="radion_btn">
                                <input type="radio" id="f-option6" name="thanhtoan" value="Thanh toán bằng PayPal"
                                    required />
                                <label for="f-option6"> PayPal </label>
                                <img src="{{ asset('assets/clients/img/product/single-product/card.jpg') }}"
                                    alt="" />
                                <div class="check"></div>
                            </div>
                        </div>
                        <button type="submit" class="main_btn" style="margin: auto;margin-top: 10px">Đặt hàng</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</section>
<!--================End Checkout Area =================-->
@section('javascript')
    <script>
        var citis = document.getElementById("city");
        var districts = document.getElementById("district");
        var wards = document.getElementById("ward");

        $.ajax({
            url: "{{ asset('assets/clients/json/data.json') }}",
            method: "GET",
            dataType: "json",
            success: function(result) {
                renderCity(result);
            }
        });

        function renderCity(data) {
            for (const x of data) {
                citis.options[citis.options.length] = new Option(x.Name, x.Id);
            }
            citis.onchange = function() {
                districts.length = 1;
                wards.length = 1;
                if (this.value != "") {
                    const result = data.filter(n => n.Id === this.value);

                    for (const k of result[0].Districts) {
                        districts.options[districts.options.length] = new Option(k.Name, k.Id);
                    }
                }
            };
            districts.onchange = function() {
                wards.length = 1;
                const dataCity = data.filter((n) => n.Id === citis.value);
                if (this.value != "") {
                    const dataWards = dataCity[0].Districts.filter(n => n.Id === this.value)[0].Wards;

                    for (const w of dataWards) {
                        wards.options[wards.options.length] = new Option(w.Name, w.Id);
                    }
                }
            };
        }

        // validation form
        const checkoutForm = document.getElementById('form-checkout');
        const nameField = document.getElementById('name');
        const sdtField = document.getElementById('sdt');
        const addressField = document.getElementById('address');

        const citySelect = document.getElementById('city');
        const districtSelect = document.getElementById('district');
        const wardSelect = document.getElementById('ward');

        const radioButtons = document.getElementsByName('thanhtoan');
        // add required attribute to necessary fields
        nameField.required = true;
        sdtField.required = true;
        addressField.required = true;

        citySelect.required = true;
        districtSelect.required = true;
        wardSelect.required = true;

        radioButtons.required = true;
        // add event listener for changes in select values
        citySelect.addEventListener('change', validateCitySelect);
        districtSelect.addEventListener('change', validateDistrictSelect);
        wardSelect.addEventListener('change', validateWardSelect);
        // add event listener for form submission
        checkoutForm.addEventListener('submit', function(event) {
            // check if all required fields are filled

            // check if at least one radio button is checked
            let radioButtonChecked = false;
            for (let i = 0; i < radioButtons.length; i++) {
                if (radioButtons[i].checked) {
                    radioButtonChecked = true;
                    break;
                }
            }
            if (!radioButtonChecked || !nameField.checkValidity() || !sdtField.checkValidity() || !addressField
                .checkValidity()) {
                event.preventDefault();
            }

            // define validation functions for each select
            function validateCitySelect() {
                if (citySelect.value === '') {
                    citySelect.setCustomValidity('Vui lòng chọn tỉnh thành');
                } else {
                    citySelect.setCustomValidity('');
                }
            }

            function validateDistrictSelect() {
                if (districtSelect.value === '') {
                    districtSelect.setCustomValidity('Vui lòng chọn quận huyện');
                } else {
                    districtSelect.setCustomValidity('');
                }
            }

            function validateWardSelect() {
                if (wardSelect.value === '') {
                    wardSelect.setCustomValidity('Vui lòng chọn phường xã');
                } else {
                    wardSelect.setCustomValidity('');
                }
            }
        });
    </script>
@endsection
@include('clients.blocks.footer')
