<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProductAdminController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Quản lý Sản phẩm & Kho hàng - Eiser Admin';

        $categories = DB::table('categories')->get();
        $brands = DB::table('brand')->get();
        $statuses = DB::table('productstatus')->get();

        // Thống kê nhanh
        $totalCount = DB::table('product')->count();
        $lowStockCount = DB::table('product')->where('Quantity', '>', 0)->where('Quantity', '<=', 5)->count();
        $outOfStockCount = DB::table('product')->where('Quantity', '<=', 0)->count();

        $query = DB::table('product')
            ->leftJoin('categories', 'product.CategoryID', '=', 'categories.CategoryID')
            ->leftJoin('brand', 'product.BrandID', '=', 'brand.BrandID')
            ->leftJoin('productstatus', 'product.ProductStatusID', '=', 'productstatus.ProductStatusID')
            ->select(
                'product.*',
                'categories.CategoryName',
                'brand.BrandName',
                'productstatus.ProductStatusName'
            );

        // Tìm kiếm theo tên hoặc mã SP
        if ($request->filled('search')) {
            $search = trim($request->search);
            $cleanId = preg_replace('/[^0-9]/', '', $search);
            $query->where(function ($q) use ($search, $cleanId) {
                if ($cleanId !== '' && str_starts_with(strtoupper($search), '#SP') || is_numeric($search)) {
                    $q->orWhere('product.ProductID', (int)$cleanId);
                }
                $q->orWhere('product.ProductName', 'like', "%{$search}%");
            });
        }

        // Lọc theo danh mục
        if ($request->filled('category_id') && $request->category_id !== 'all') {
            $query->where('product.CategoryID', $request->category_id);
        }

        // Lọc theo thương hiệu
        if ($request->filled('brand_id') && $request->brand_id !== 'all') {
            $query->where('product.BrandID', $request->brand_id);
        }

        // Lọc theo tồn kho
        if ($request->filled('stock') && $request->stock !== 'all') {
            if ($request->stock === 'in_stock') {
                $query->where('product.Quantity', '>', 5);
            } elseif ($request->stock === 'low_stock') {
                $query->where('product.Quantity', '>', 0)->where('product.Quantity', '<=', 5);
            } elseif ($request->stock === 'out_of_stock') {
                $query->where('product.Quantity', '<=', 0);
            }
        }

        $products = $query->orderBy('product.ProductID', 'desc')->paginate(10)->withQueryString();

        return view('admin.products.index', compact(
            'title',
            'products',
            'categories',
            'brands',
            'statuses',
            'totalCount',
            'lowStockCount',
            'outOfStockCount'
        ));
    }

    public function create()
    {
        $title = 'Thêm Sản phẩm mới - Eiser Admin';
        $categories = DB::table('categories')->get();
        $brands = DB::table('brand')->get();
        $statuses = DB::table('productstatus')->get();

        return view('admin.products.create', compact('title', 'categories', 'brands', 'statuses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ProductName' => 'required|string|max:50',
            'CategoryID' => 'required|integer',
            'BrandID' => 'required|integer',
            'ProductStatusID' => 'required|integer',
            'Price' => 'required|numeric|min:0',
            'Quantity' => 'required|integer|min:0',
            'ProductDesc' => 'nullable|string',
            'Picture' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'Picture2' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'ProductName.required' => 'Vui lòng nhập tên sản phẩm.',
            'ProductName.max' => 'Tên sản phẩm tối đa 50 ký tự.',
            'Price.required' => 'Vui lòng nhập giá bán.',
            'Quantity.required' => 'Vui lòng nhập số lượng tồn kho.',
            'Picture.required' => 'Vui lòng tải lên hình ảnh chính cho sản phẩm.',
            'Picture.image' => 'Tệp tải lên phải là định dạng ảnh hợp lệ (jpg, png, webp).',
            'Picture.max' => 'Dung lượng ảnh không được vượt quá 2MB.',
        ]);

        $uploadPath = public_path('assets/admin/img/upload');
        if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0755, true);
        }

        // Xử lý ảnh chính
        $pictureName = '';
        if ($request->hasFile('Picture')) {
            $file = $request->file('Picture');
            $pictureName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadPath, $pictureName);
        }

        // Xử lý ảnh phụ (Picture2 là NOT NULL trong DB nên mặc định chuỗi rỗng)
        $picture2Name = '';
        if ($request->hasFile('Picture2')) {
            $file2 = $request->file('Picture2');
            $picture2Name = time() . '_2_' . uniqid() . '.' . $file2->getClientOriginalExtension();
            $file2->move($uploadPath, $picture2Name);
        }

        date_default_timezone_set('Asia/Ho_Chi_Minh');

        DB::table('product')->insert([
            'ProductName' => $request->ProductName,
            'CategoryID' => $request->CategoryID,
            'BrandID' => $request->BrandID,
            'ProductStatusID' => $request->ProductStatusID,
            'Price' => $request->Price,
            'Quantity' => $request->Quantity,
            'ProductDesc' => $request->ProductDesc ?? '',
            'Picture' => $pictureName,
            'Picture2' => $picture2Name,
            'UpdateDate' => date('H:i d-m-Y'),
            'QuantityRating' => 0,
        ]);

        return redirect()->route('admin.products.index')->with('msg_success', 'Đã thêm sản phẩm "' . $request->ProductName . '" thành công!');
    }

    public function edit($id)
    {
        $product = DB::table('product')->where('ProductID', $id)->first();
        if (!$product) {
            return redirect()->route('admin.products.index')->with('msg_error', 'Không tìm thấy sản phẩm cần chỉnh sửa!');
        }

        $title = 'Chỉnh sửa Sản phẩm: ' . $product->ProductName;
        $categories = DB::table('categories')->get();
        $brands = DB::table('brand')->get();
        $statuses = DB::table('productstatus')->get();

        return view('admin.products.edit', compact('title', 'product', 'categories', 'brands', 'statuses'));
    }

    public function update(Request $request, $id)
    {
        $product = DB::table('product')->where('ProductID', $id)->first();
        if (!$product) {
            return redirect()->route('admin.products.index')->with('msg_error', 'Không tìm thấy sản phẩm!');
        }

        $request->validate([
            'ProductName' => 'required|string|max:50',
            'CategoryID' => 'required|integer',
            'BrandID' => 'required|integer',
            'ProductStatusID' => 'required|integer',
            'Price' => 'required|numeric|min:0',
            'Quantity' => 'required|integer|min:0',
            'ProductDesc' => 'nullable|string',
            'Picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'Picture2' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'ProductName.required' => 'Vui lòng nhập tên sản phẩm.',
            'ProductName.max' => 'Tên sản phẩm tối đa 50 ký tự.',
            'Price.required' => 'Vui lòng nhập giá bán.',
            'Quantity.required' => 'Vui lòng nhập số lượng tồn kho.',
            'Picture.image' => 'Tệp tải lên phải là ảnh hợp lệ.',
            'Picture.max' => 'Dung lượng ảnh không được vượt quá 2MB.',
        ]);

        $uploadPath = public_path('assets/admin/img/upload');
        if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0755, true);
        }

        $pictureName = $product->Picture;
        if ($request->hasFile('Picture')) {
            $file = $request->file('Picture');
            $pictureName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadPath, $pictureName);
        }

        $picture2Name = $product->Picture2 ?? '';
        if ($request->hasFile('Picture2')) {
            $file2 = $request->file('Picture2');
            $picture2Name = time() . '_2_' . uniqid() . '.' . $file2->getClientOriginalExtension();
            $file2->move($uploadPath, $picture2Name);
        }

        date_default_timezone_set('Asia/Ho_Chi_Minh');

        DB::table('product')->where('ProductID', $id)->update([
            'ProductName' => $request->ProductName,
            'CategoryID' => $request->CategoryID,
            'BrandID' => $request->BrandID,
            'ProductStatusID' => $request->ProductStatusID,
            'Price' => $request->Price,
            'Quantity' => $request->Quantity,
            'ProductDesc' => $request->ProductDesc ?? '',
            'Picture' => $pictureName,
            'Picture2' => $picture2Name,
            'UpdateDate' => date('H:i d-m-Y'),
        ]);

        return redirect()->route('admin.products.index')->with('msg_success', 'Đã cập nhật thông tin sản phẩm "' . $request->ProductName . '" thành công!');
    }

    public function destroy($id)
    {
        $product = DB::table('product')->where('ProductID', $id)->first();
        if (!$product) {
            return redirect()->route('admin.products.index')->with('msg_error', 'Sản phẩm không tồn tại!');
        }

        // Kiểm tra ràng buộc đơn hàng để bảo toàn lịch sử giao dịch
        $hasOrder = DB::table('orderdetail')->where('ProductID', $id)->exists();
        if ($hasOrder) {
            return redirect()->route('admin.products.index')
                ->with('msg_error', "Không thể xóa sản phẩm \"{$product->ProductName}\" vì đã phát sinh đơn hàng. Vui lòng chỉnh sửa tồn kho về 0 để ngừng kinh doanh!");
        }

        DB::beginTransaction();
        try {
            DB::table('carts')->where('ProductID', $id)->delete();
            DB::table('productreview')->where('ProductID', $id)->delete();
            DB::table('rating')->where('ProductID', $id)->delete();
            DB::table('product')->where('ProductID', $id)->delete();

            DB::commit();
            return redirect()->route('admin.products.index')->with('msg_success', "Đã xóa sản phẩm \"{$product->ProductName}\" khỏi hệ thống.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.products.index')->with('msg_error', 'Lỗi khi xóa sản phẩm: ' . $e->getMessage());
        }
    }
}
