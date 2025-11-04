<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ads;
use Illuminate\Support\Facades\File as FileFacade;
use Illuminate\Validation\Rule;

class AdsController extends Controller
{

    public function index(Request $request)
    {
        $query = Ads::orderBy('id', 'DESC');

        if ($request->has('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        $perPage = $request->input('perPage', 20);

        $queryParams = $request->except('page');
        if ($perPage != 20) {
            $queryParams['perPage'] = $perPage;
        }

        $ads = $query->paginate($perPage);

        $location = $request->input('location', '');

        $ads->setPath(asset('/ads') . '?' . http_build_query($queryParams));

        return view('admin/allAdsList', [
            'ads' => $ads,
            'location' => $location,
            'perPage' => $perPage
        ]);
    }


    public function addAds(Request $request)
    {
        return view('admin/addCustomAds');
    }

    public function storeAd(Request $request)
    {

        $rules = [
            'location' => 'required|string',
            'ads_type' => 'required|in:0,1',
            'page_type' => ['required', Rule::in(\App\Models\Ads::PAGE_TYPES)],
        ];

        if ($request->ads_type == 1) {
            $rules['GoogleClient'] = 'required|string';
            $rules['GoogleSlot'] = 'required|string';
        } else {
            $rules['link'] = 'required|url';
            $rules['image_file'] = 'required|file|mimes:jpeg,jpg,png,webp|max:200';
        }

        if ($request->hasFile('image_file')) {
            $rules['image_file'] = 'file|mimes:jpeg,jpg,png,webp|max:200';
        }


        $messages = [
            'location.required' => 'Location is required.',
            'ads_type.required' => 'Ad type is required.',
            'page_type.required' => 'Page type is required.',
            'GoogleClient.required' => 'Google Client ID is required.',
            'GoogleSlot.required' => 'Google Slot ID is required.',
            'link.required' => 'Custom link is required.',
            'image_file.required' => 'Ad image is required.',
            'image_file.mimes' => 'Only JPG, JPEG, PNG, or WEBP formats are allowed.',
            'image_file.max' => 'Image size must not exceed 200 KB.',
        ];

        $request->validate($rules, $messages);

        $filePath = null;
        $fileName = null;

        if ($request->hasFile('image_file')) {
            $image = $request->file('image_file');

            $year = date('Y');
            $month = date('m');
            $basePath = public_path("file/ads/$year/$month");

            FileFacade::makeDirectory($basePath, 0755, true, true);

            $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $cleanName = str_replace(' ', '_', $originalName);
            $cleanName = preg_replace('/[^A-Za-z0-9_\-]/', '', $cleanName);
            $fileName = $cleanName . '_' . time() . '.' . $image->getClientOriginalExtension();

            $image->move($basePath, $fileName);

            $filePath = "file/ads/$year/$month";
        }

        Ads::create([
            'location' => trim($request->location),
            'is_google_ad' => $request->ads_type,
            'custom_image' => $fileName,
            'file_path' => $filePath,
            'page_type' => $request->page_type,
            'custom_link' => $request->link,
            'google_client' => $request->GoogleClient,
            'google_slot' => $request->GoogleSlot,
        ]);

        return redirect('/ads')
            ->with('success', 'Ad saved successfully!');
    }

    public function edit($id)
    {
        $Ads = Ads::find($id);
        if (!$Ads) {
            dump("No file found with ID: " . $id);
        }

        return view('admin/editCustomAds', ['ad' => $Ads]);
    }

    public function updateAd(Request $request, $id)
    {
        $ad = Ads::findOrFail($id);

        $rules = [
            'location' => 'required|string',
            'ads_type' => 'required|in:0,1',
            'page_type' => ['required', Rule::in(\App\Models\Ads::PAGE_TYPES)],
        ];

        // Check current and new file condition
        $hasNewImage = $request->hasFile('image_file');
        $hasExistingImage = !empty($ad->custom_image) && !empty($ad->file_path);

        if ($request->ads_type == 1) { // Google Ads
            $rules['GoogleClient'] = 'required|string';
            $rules['GoogleSlot'] = 'required|string';

            if ($hasNewImage) {
                $rules['image_file'] = 'file|mimes:jpeg,jpg,png,webp|max:200';
            }
        } else { // Custom Ads
            $rules['link'] = 'required|url';

            if (!$hasExistingImage && !$hasNewImage) {
                $rules['image_file'] = 'required|file|mimes:jpeg,jpg,png,webp|max:200';
            } elseif ($hasNewImage) {
                $rules['image_file'] = 'file|mimes:jpeg,jpg,png,webp|max:200';
            }
        }

        $messages = [
            'location.required' => 'Location is required.',
            'ads_type.required' => 'Ad type is required.',
            'page_type.required' => 'Page type is required.',
            'GoogleClient.required' => 'Google Client ID is required.',
            'GoogleSlot.required' => 'Google Slot ID is required.',
            'link.required' => 'Custom link is required.',
            'image_file.required' => 'Ad image is required.',
            'image_file.mimes' => 'Only JPG, JPEG, PNG, or WEBP formats are allowed.',
            'image_file.max' => 'Image size must not exceed 200 KB.',
        ];

        $request->validate($rules, $messages);

        $fileName = $ad->custom_image;
        $filePath = $ad->file_path;

        // Upload and replace image if new one is provided
        if ($hasNewImage) {
            // Delete old image
            if ($ad->file_path && $ad->custom_image) {
                $oldImage = public_path($ad->file_path . '/' . $ad->custom_image);
                if (FileFacade::exists($oldImage)) {
                    FileFacade::delete($oldImage);
                }
            }

            $image = $request->file('image_file');
            $year = date('Y');
            $month = date('m');
            $basePath = public_path("file/ads/$year/$month");

            FileFacade::makeDirectory($basePath, 0755, true, true);

            $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $cleanName = preg_replace('/[^A-Za-z0-9_\-]/', '', str_replace(' ', '_', $originalName));
            $fileName = $cleanName . '_' . time() . '.' . $image->getClientOriginalExtension();
            $image->move($basePath, $fileName);

            $filePath = "file/ads/$year/$month";
        }

        $ad->update([
            'location' => trim($request->location),
            'is_google_ad' => $request->ads_type,
            'page_type' => $request->page_type,
            'google_client' => $request->GoogleClient,
            'google_slot' => $request->GoogleSlot,
            'custom_link' => $request->link,
            'custom_image' => $fileName,
            'file_path' => $filePath,
        ]);

        return redirect('/ads')
            ->with('success', 'Ad has been updated successfully!');
    }
}
