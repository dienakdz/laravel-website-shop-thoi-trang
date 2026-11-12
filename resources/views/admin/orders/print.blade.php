<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 30px;
            color: #333;
            font-size: 14px;
            line-height: 1.6;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            border: 1px solid #eee;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #71cd14;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .brand {
            font-size: 24px;
            font-weight: bold;
            color: #71cd14;
        }
        .title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 20px 0;
            color: #111;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .table th {
            background-color: #f8f9fa;
        }
        .total-box {
            text-align: right;
            font-size: 16px;
            font-weight: bold;
            margin-top: 10px;
        }
        .signatures {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            text-align: center;
            margin-top: 50px;
        }
        .sign-title {
            font-weight: bold;
            margin-bottom: 60px;
        }
        .no-print {
            text-align: center;
            margin-bottom: 20px;
        }
        .btn-print {
            background: #71cd14;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 14px;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
        }
        @media print {
            .no-print { display: none; }
            .invoice-box { border: none; box-shadow: none; padding: 0; }
            body { padding: 0; }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button class="btn-print" onclick="window.print()">🖨️ In hóa đơn này</button>
        <button class="btn-print" style="background:#64748b;" onclick="window.close()">Đóng cửa sổ</button>
    </div>

    <div class="invoice-box">
        <div class="header">
            <div>
                <div class="brand">EISER FASHION</div>
                <div>Website thời trang chính hãng Eiser</div>
                <div>Hotline: 0967.468.703 • Email: support@shopthoitrang.vn</div>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 16px; font-weight: bold;">HÓA ĐƠN BÁN HÀNG</div>
                <div>Mã đơn: <strong>#DH-{{ str_pad($order->OrderID, 4, '0', STR_PAD_LEFT) }}</strong></div>
                <div>Ngày: {{ $order->OrderDate }}</div>
            </div>
        </div>

        <div class="info-grid">
            <div>
                <div style="font-weight: bold; margin-bottom: 6px;">THÔNG TIN NGƯỜI NHẬN:</div>
                <div>Họ và tên: <strong>{{ $order->Fulname ?? 'Khách vãng lai' }}</strong></div>
                <div>Số điện thoại: {{ $order->Phonenumber ?? '---' }}</div>
                <div>Địa chỉ: {{ $order->Address ?? '---' }}</div>
            </div>
            <div>
                <div style="font-weight: bold; margin-bottom: 6px;">PHƯƠNG THỨC THANH TOÁN:</div>
                <div>Hình thức: <strong>{{ $order->Shippingtype ?? 'Thanh toán khi nhận hàng (COD)' }}</strong></div>
                <div>Trạng thái: 
                    @if($order->Status == '1') Chờ xác nhận
                    @elseif($order->Status == '2') Đang chuẩn bị hàng
                    @elseif($order->Status == '3') Đang giao hàng
                    @elseif($order->Status == '4') Đã hoàn thành
                    @else Đã hủy
                    @endif
                </div>
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th style="width: 40px; text-align: center;">STT</th>
                    <th>Tên sản phẩm</th>
                    <th style="text-align: center;">Số lượng</th>
                    <th style="text-align: right;">Đơn giá</th>
                    <th style="text-align: right;">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @php $stt = 1; @endphp
                @forelse($items as $item)
                    <tr>
                        <td style="text-align: center;">{{ $stt++ }}</td>
                        <td>{{ $item->ProductName ?? 'Sản phẩm mã #' . $item->ProductID }}</td>
                        <td style="text-align: center;">{{ $item->QuantityOrdered }}</td>
                        <td style="text-align: right;">{{ number_format($item->Price / max(1, $item->QuantityOrdered), 0, ',', '.') }} ₫</td>
                        <td style="text-align: right;">{{ number_format($item->Price, 0, ',', '.') }} ₫</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center;">Chưa có sản phẩm chi tiết</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="total-box">
            <div>Tổng tiền hàng: {{ number_format($order->Totalprice, 0, ',', '.') }} ₫</div>
            <div>Phí vận chuyển: 0 ₫ (Miễn phí)</div>
            <div style="font-size: 18px; color: #71cd14; margin-top: 6px;">
                TỔNG CỘNG THANH TOÁN: {{ number_format($order->Totalprice, 0, ',', '.') }} ₫
            </div>
        </div>

        <div class="signatures">
            <div>
                <div class="sign-title">Khách hàng nhận</div>
                <div>(Ký và ghi rõ họ tên)</div>
            </div>
            <div>
                <div class="sign-title">Nhân viên giao hàng</div>
                <div>(Ký và ghi rõ họ tên)</div>
            </div>
            <div>
                <div class="sign-title">Người lập phiếu</div>
                <div>(Ký và ghi rõ họ tên)</div>
            </div>
        </div>
    </div>
</body>
</html>
