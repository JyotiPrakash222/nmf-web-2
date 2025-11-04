<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HomeSection;


class BannerHomePageController extends Controller
{
    public function index(Request $request)
    {
        $get_banner = HomeSection::where('type', 'banner')->get();
        $homeSectionStatus = HomeSection::where('title', 'ShowBannerAboveTopStory')->first();

        return view('admin/bannerHomePageControl', [
            'get_banner' => $get_banner,
            'homeSectionStatus' => $homeSectionStatus
        ]);
    }
    public function updateBannerDisplayMode(Request $request)
    {
        $enabledIds = $request->input('show_banner', []);

        // Disable all banners with titles 'Banner' or 'BannerMobile'
        HomeSection::whereIn('title', ['Banner', 'BannerMobile'])->update(['status' => 0]);

        // Enable only the selected banners
        if (!empty($enabledIds)) {
            HomeSection::whereIn('id', $enabledIds)->update(['status' => 1]);
        }

        // Check how many are now enabled
        $enabledCount = HomeSection::whereIn('title', ['Banner', 'BannerMobile'])
            ->where('status', 1)
            ->count();

        // Set message based on status
        $message = $enabledCount > 0 ? 'enabled' : 'disabled';

        return response()->json([
            'success' => true,
            'status' => $message
        ]);
    }

    public function bannerUpdate(Request $request)
    {
        $status = $request->input('active_status');

        $section = HomeSection::updateOrCreate(
            ['title' => 'ShowBannerAboveTopStory'],
            ['status' => $status]
        );

        try {
            app(\App\Services\ExportHome::class)->run();
        } catch (\Throwable $e) {
            \Log::error('ExportHome failed', ['error' => $e->getMessage()]);
        }

        if ($section) {
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Update failed'], 500);
    }
}
