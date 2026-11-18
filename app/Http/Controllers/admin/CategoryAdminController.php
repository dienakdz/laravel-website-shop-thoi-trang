<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CategoryAdminController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Quản lý Danh mục & Thương hiệu - DDien Admin';
        $activeTab = $request->query('tab', 'categories');

        // Danh sách Danh mục kèm số lượng sản phẩm
        $categories = DB::table('categories')
            ->leftJoin('product', 'categories.CategoryID', '=', 'product.CategoryID')
            ->select(
                'categories.*',
                DB::raw('COUNT(product.ProductID) as total_products')
            )
            ->groupBy('categories.CategoryID', 'categories.CategoryName', 'categories.Picture', 'categories.Description')
            ->orderBy('categories.CategoryID', 'asc')
            ->get();

        // Danh sách Thương hiệu kèm số lượng sản phẩm
        $brands = DB::table('brand')
            ->leftJoin('product', 'brand.BrandID', '=', 'product.BrandID')
            ->select(
                'brand.*',
                DB::raw('COUNT(product.ProductID) as total_products')
            )
            ->groupBy('brand.BrandID', 'brand.BrandName', 'brand.Logo', 'brand.BrandDesc')
            ->orderBy('brand.BrandID', 'asc')
            ->get();

        return view('admin.categories.index', compact('title', 'categories', 'brands', 'activeTab'));
    }

    // ==================== XỬ LÝ DANH MỤC ====================
    public function storeCategory(Request $request)
    {
        $request->validate([
            'CategoryName' => 'required|string|max:60',
            'Picture' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'Description' => 'nullable|string|max:255',
        ], [
            'CategoryName.required' => 'Vui lòng nhập tên danh mục.',
            'CategoryName.max' => 'Tên danh mục không vượt quá 60 ký tự.',
            'Picture.image' => 'Ảnh đại diện phải là tệp hình ảnh hợp lệ.',
            'Picture.max' => 'Dung lượng ảnh tối đa 2MB.',
        ]);

        $uploadPath = public_path('assets/admin/img/upload');
        if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0755, true);
        }

        $pictureName = '';
        if ($request->hasFile('Picture')) {
            $file = $request->file('Picture');
            $pictureName = 'danhmuc_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadPath, $pictureName);
        }

        DB::table('categories')->insert([
            'CategoryName' => $request->CategoryName,
            'Picture' => $pictureName,
            'Description' => $request->Description ?? '',
        ]);

        return redirect()->route('admin.categories.index', ['tab' => 'categories'])
            ->with('msg_success', 'Đã thêm danh mục "' . $request->CategoryName . '" thành công!');
    }

    public function updateCategory(Request $request, $id)
    {
        $cat = DB::table('categories')->where('CategoryID', $id)->first();
        if (!$cat) {
            return back()->with('msg_error', 'Không tìm thấy danh mục!');
        }

        $request->validate([
            'CategoryName' => 'required|string|max:60',
            'Picture' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'Description' => 'nullable|string|max:255',
        ]);

        $uploadPath = public_path('assets/admin/img/upload');
        $pictureName = $cat->Picture;

        if ($request->hasFile('Picture')) {
            $file = $request->file('Picture');
            $pictureName = 'danhmuc_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadPath, $pictureName);
        }

        DB::table('categories')->where('CategoryID', $id)->update([
            'CategoryName' => $request->CategoryName,
            'Picture' => $pictureName,
            'Description' => $request->Description ?? '',
        ]);

        return redirect()->route('admin.categories.index', ['tab' => 'categories'])
            ->with('msg_success', 'Đã cập nhật danh mục "' . $request->CategoryName . '" thành công!');
    }

    public function destroyCategory($id)
    {
        $cat = DB::table('categories')->where('CategoryID', $id)->first();
        if (!$cat) {
            return back()->with('msg_error', 'Không tìm thấy danh mục!');
        }

        // Kiểm tra ràng buộc sản phẩm
        $hasProducts = DB::table('product')->where('CategoryID', $id)->count();
        if ($hasProducts > 0) {
            return redirect()->route('admin.categories.index', ['tab' => 'categories'])
                ->with('msg_error', "Không thể xóa danh mục \"{$cat->CategoryName}\" vì đang có {$hasProducts} sản phẩm thuộc danh mục này!");
        }

        DB::table('categories')->where('CategoryID', $id)->delete();

        return redirect()->route('admin.categories.index', ['tab' => 'categories'])
            ->with('msg_success', "Đã xóa danh mục \"{$cat->CategoryName}\" thành công.");
    }

    // ==================== XỬ LÝ THƯƠNG HIỆU ====================
    public function storeBrand(Request $request)
    {
        $request->validate([
            'BrandName' => 'required|string|max:40',
            'Logo' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'BrandDesc' => 'nullable|string|max:255',
        ], [
            'BrandName.required' => 'Vui lòng nhập tên thương hiệu.',
            'BrandName.max' => 'Tên thương hiệu không vượt quá 40 ký tự.',
            'Logo.required' => 'Vui lòng tải lên logo thương hiệu.',
            'Logo.image' => 'Logo phải là tệp ảnh hợp lệ.',
        ]);

        $uploadPath = public_path('assets/admin/img/upload');
        if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0755, true);
        }

        $logoName = '';
        if ($request->hasFile('Logo')) {
            $file = $request->file('Logo');
            $logoName = 'brand_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadPath, $logoName);
        }

        DB::table('brand')->insert([
            'BrandName' => $request->BrandName,
            'Logo' => $logoName,
            'BrandDesc' => $request->BrandDesc ?? '',
        ]);

        return redirect()->route('admin.categories.index', ['tab' => 'brands'])
            ->with('msg_success', 'Đã thêm thương hiệu "' . $request->BrandName . '" thành công!');
    }

    public function updateBrand(Request $request, $id)
    {
        $brand = DB::table('brand')->where('BrandID', $id)->first();
        if (!$brand) {
            return back()->with('msg_error', 'Không tìm thấy thương hiệu!');
        }

        $request->validate([
            'BrandName' => 'required|string|max:40',
            'Logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'BrandDesc' => 'nullable|string|max:255',
        ]);

        $uploadPath = public_path('assets/admin/img/upload');
        $logoName = $brand->Logo;

        if ($request->hasFile('Logo')) {
            $file = $request->file('Logo');
            $logoName = 'brand_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadPath, $logoName);
        }

        DB::table('brand')->where('BrandID', $id)->update([
            'BrandName' => $request->BrandName,
            'Logo' => $logoName,
            'BrandDesc' => $request->BrandDesc ?? '',
        ]);

        return redirect()->route('admin.categories.index', ['tab' => 'brands'])
            ->with('msg_success', 'Đã cập nhật thương hiệu "' . $request->BrandName . '" thành công!');
    }

    public function destroyBrand($id)
    {
        $brand = DB::table('brand')->where('BrandID', $id)->first();
        if (!$brand) {
            return back()->with('msg_error', 'Không tìm thấy thương hiệu!');
        }

        // Kiểm tra ràng buộc sản phẩm
        $hasProducts = DB::table('product')->where('BrandID', $id)->count();
        if ($hasProducts > 0) {
            return redirect()->route('admin.categories.index', ['tab' => 'brands'])
                ->with('msg_error', "Không thể xóa thương hiệu \"{$brand->BrandName}\" vì đang có {$hasProducts} sản phẩm thuộc thương hiệu này!");
        }

        DB::table('brand')->where('BrandID', $id)->delete();

        return redirect()->route('admin.categories.index', ['tab' => 'brands'])
            ->with('msg_success', "Đã xóa thương hiệu \"{$brand->BrandName}\" thành công.");
    }
}
