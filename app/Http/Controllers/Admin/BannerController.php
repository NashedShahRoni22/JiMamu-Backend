<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;
use function Termwind\render;

class BannerController extends Controller
{
    public function index(){
       // return  Inertia::render('banner/create');
        $banners = Banner::all();
        return inertia('banner/index', [
            'banners' => $banners,
        ]);
    }
    public function create(){
       return  Inertia::render('banner/create');
    }

    public function store(Request $request)
    {
        // ✅ Validate the request
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // ✅ Store image in storage/app/public/banners
        $file     = $request->file('image');
        $filename = time().'_'.$file->getClientOriginalName(); // keep original name with timestamp
        $path     = $file->storeAs('banners', $filename, 'public');

        // Save into DB
        Banner::create([
            'image_name' => $path, // e.g. banners/1727438273_myphoto.jpg
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Banner uploaded successfully!',
            'path' => $path,
            'url' => Storage::url($path), // this gives full public URL
        ]);
    }

    public function edit($id){
        $banner = Banner::findOrFail($id);

        return inertia('banner/edit', [
            'banner' => $banner,
        ]);

    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        $banner = Banner::findOrFail($id);

        // Update image if uploaded
        if ($request->hasFile('image')) {
            $file = $request->file('image');

            // Create unique filename with timestamp + original name
            $filename = time() . '_' . $file->getClientOriginalName();

            // Store in 'banners' folder inside storage/app/public
            $path = $file->storeAs('banners', $filename, 'public');

            // Save full path with storage prefix in DB
            $banner->image_name = $path;
        }


        $banner->status = $request->status;
        $banner->save();

        return redirect()->route('banner.index')->with('success', 'Banner updated successfully.');
    }
    public function destroy($id){

    }
    public function getAllBanner()
    {
        $banners = Banner::all()->map(function ($banner) {
            return [
                'id' => $banner->id,
                'status' => $banner->status,
                'image_url' => $banner->image_name ? URL::to('storage/'.$banner->image_name) : null, // full URL
            ];
        });

        return sendResponse(true, 'Banners fetched successfully.', $banners, 200);
    }

}

