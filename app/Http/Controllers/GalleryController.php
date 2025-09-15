<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\GalleryImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GalleryController extends Controller
{
    public function index()
    {
        $galleries = Gallery::with('images')->get();
        return view('admin.galleryAdmin.index', compact('galleries'));
    }
    public function create()
    {
        return view('admin.galleryAdmin.create');
    }

    public function store(Request $request)
    {
        Log::info('Store method called');
        Log::info('Request data', ['request' => $request->all()]);

        $validator = \Validator::make($request->all(), [
            'gallery_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'upload_imgs.*' => 'required|image|mimes:jpeg,png,jpg|max:4096',
        ]);

        if ($validator->fails()) {
            return back()->with('error', 'Validation failed. Please check your inputs. Note that you can only upload 4mb images')->withErrors($validator)->withInput();
        }

        Log::info('Validation passed');

        // Create the gallery
        $gallery = Gallery::create([
            'name' => $request->input('gallery_name'),
            'description' => $request->input('description'),
        ]);

        Log::info('Gallery created', ['gallery' => $gallery]);

        // Handle the image uploads
        if ($request->hasFile('upload_imgs')) {
            foreach ($request->file('upload_imgs') as $image) {
                $folderName = 'admin_gallery/' . $gallery->name;
                $path = $image->store($folderName, 'public');

                // Create a new GalleryImage record
                $galleryImg = GalleryImage::create([
                    'gallery_id' => $gallery->id,
                    'image_path' => $path,
                ]);

                Log::info('Gallery image created', ['galleryImg' => $galleryImg]);
            }
        }

        return redirect()->route('gallery.index')->with('success', 'Gallery created successfully.');
    }

    public function update(Request $request, $id)
    {
        Log::info('Update method called');
        Log::info('Request data', ['request' => $request->all()]);

        $validator = \Validator::make($request->all(), [
            'gallery_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Log::info('Validation passed');

        // Find the gallery
        $gallery = Gallery::findOrFail($id);

        if ($request->has('gallery_name')) {
            $gallery->name = $request->input('gallery_name');
        }

        if ($request->has('description')) {
            $gallery->description = $request->input('description');
        }

        $gallery->save();

        Log::info('Gallery updated', ['gallery' => $gallery]);

        return back()->with('success', 'Gallery updated successfully.');
    }

    public function show($id)
    {
        $gallery = Gallery::with('images')->findOrFail($id);
        return view('admin.galleryAdmin.show', compact('gallery'));
    }

    public function edit($id)
    {
        $gallery = Gallery::with('images')->findOrFail($id);
        return view('admin.galleryAdmin.edit', compact('gallery'));
    }



    public function destroy($id)
    {
        Log::info('Destroy method called');

        // Find the gallery
        $gallery = Gallery::findOrFail($id);

        // Delete associated images
        foreach ($gallery->images as $image) {
            // Delete the image file from storage
            \Storage::disk('public')->delete($image->image_path);

            // Delete the image record from the database
            $image->delete();
        }

        // Delete the gallery
        $gallery->delete();

        Log::info('Gallery and associated images deleted', ['gallery' => $gallery]);

        return back()->with('success', 'Gallery deleted successfully.');
    }


}
