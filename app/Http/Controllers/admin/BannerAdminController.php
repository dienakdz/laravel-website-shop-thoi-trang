<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class BannerAdminController extends Controller
{
    public function index()
    {
        $title = 'Quản lý Banner Trang chủ - Eiser Admin';
        $banners = DB::table('banner')->orderBy('is_active', 'desc')->orderBy('id', 'asc')->get();

        // Helper map banner image URL
        $mappedBanners = $banners->map(function ($item) {
            $imgName = $item->image_banner;
            $url = asset('assets/admin/img/' . $imgName);
            if (!empty($imgName) && file_exists(public_path('assets/admin/img/upload/' . $imgName))) {
                $url = asset('assets/admin/img/upload/' . $imgName);
            }
            $item->image_url = $url;
            return $item;
        });

        $activeBanner = $mappedBanners->firstWhere('is_active', 1) ?? $mappedBanners->first();

        return view('admin.banners.index', compact('title', 'mappedBanners', 'activeBanner'));
    }

    public function setActive($id)
    {
        $banner = DB::table('banner')->where('id', $id)->first();
        if (!$banner) {
            return redirect()->route('admin.banners.index')->with('msg_error', 'Banner không tồn tại!');
        }

        DB::table('banner')->update(['is_active' => 0]);
        DB::table('banner')->where('id', $id)->update(['is_active' => 1]);

        return redirect()->route('admin.banners.index')->with('msg_success', "Đã kích hoạt banner '{$banner->name_banner}' hiển thị chính trên trang chủ!");
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_banner' => 'required|string|max:255',
            'content_banner' => 'nullable|string|max:500',
            'image_banner' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ], [
            'name_banner.required' => 'Vui lòng nhập tiêu đề banner',
            'image_banner.required' => 'Vui lòng chọn hình ảnh banner',
            'image_banner.image' => 'Tệp tải lên phải là hình ảnh',
            'image_banner.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, webp',
            'image_banner.max' => 'Dung lượng hình ảnh không được vượt quá 5MB',
        ]);

        $imageName = 'banner_' . time() . '_' . uniqid() . '.' . $request->file('image_banner')->getClientOriginalExtension();
        $targetDir = public_path('assets/admin/img/upload');
        if (!File::isDirectory($targetDir)) {
            File::makeDirectory($targetDir, 0755, true, true);
        }

        $request->file('image_banner')->move($targetDir, $imageName);

        // Nếu là banner đầu tiên, tự động kích hoạt
        $count = DB::table('banner')->count();
        $isActive = ($count === 0) ? 1 : 0;

        DB::table('banner')->insert([
            'name_banner' => $request->name_banner,
            'content_banner' => $request->content_banner ?? '',
            'image_banner' => $imageName,
            'is_active' => $isActive,
        ]);

        return redirect()->route('admin.banners.index')->with('msg_success', 'Thêm banner chiến dịch mới thành công!');
    }

    public function update(Request $request, $id)
    {
        $banner = DB::table('banner')->where('id', $id)->first();
        if (!$banner) {
            return redirect()->route('admin.banners.index')->with('msg_error', 'Banner không tồn tại!');
        }

        $request->validate([
            'name_banner' => 'required|string|max:255',
            'content_banner' => 'nullable|string|max:500',
            'image_banner' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ], [
            'name_banner.required' => 'Vui lòng nhập tiêu đề banner',
            'image_banner.image' => 'Tệp tải lên phải là hình ảnh',
            'image_banner.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, webp',
            'image_banner.max' => 'Dung lượng hình ảnh không được vượt quá 5MB',
        ]);

        $imageName = $banner->image_banner;

        if ($request->hasFile('image_banner')) {
            $newImageName = 'banner_' . time() . '_' . uniqid() . '.' . $request->file('image_banner')->getClientOriginalExtension();
            $targetDir = public_path('assets/admin/img/upload');
            if (!File::isDirectory($targetDir)) {
                File::makeDirectory($targetDir, 0755, true, true);
            }

            $request->file('image_banner')->move($targetDir, $newImageName);

            // Xóa ảnh cũ nếu là ảnh trong thư mục upload
            $oldPath = public_path('assets/admin/img/upload/' . $imageName);
            if (File::exists($oldPath)) {
                File::delete($oldPath);
            }

            $imageName = $newImageName;
        }

        DB::table('banner')->where('id', $id)->update([
            'name_banner' => $request->name_banner,
            'content_banner' => $request->content_banner ?? '',
            'image_banner' => $imageName,
        ]);

        return redirect()->route('admin.banners.index')->with('msg_success', 'Cập nhật banner trang chủ thành công!');
    }

    public function destroy($id)
    {
        $count = DB::table('banner')->count();
        if ($count <= 1) {
            return redirect()->route('admin.banners.index')->with('msg_error', 'Hệ thống yêu cầu phải duy trì ít nhất 1 banner cho trang chủ!');
        }

        $banner = DB::table('banner')->where('id', $id)->first();
        if ($banner) {
            $wasActive = $banner->is_active;

            $oldPath = public_path('assets/admin/img/upload/' . $banner->image_banner);
            if (File::exists($oldPath)) {
                File::delete($oldPath);
            }
            DB::table('banner')->where('id', $id)->delete();

            // Nếu banner vừa xóa đang là active, kích hoạt banner đầu tiên còn lại
            if ($wasActive) {
                $firstRemaining = DB::table('banner')->first();
                if ($firstRemaining) {
                    DB::table('banner')->where('id', $firstRemaining->id)->update(['is_active' => 1]);
                }
            }
        }

        return redirect()->route('admin.banners.index')->with('msg_success', 'Đã xóa banner thành công!');
    }
}
