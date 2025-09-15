<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\GalleryImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GalleryImageController extends Controller
{
    public function store(Request $request, $galleryId)
    {
        Log::info('Store image method called');

        // Validate the request
        $validator = \Validator::make($request->all(), [
            'upload_img' => 'required|image|mimes:jpeg,png,jpg|max:4096',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Log::info('Validation passed');

        // Find the gallery
        $gallery = Gallery::findOrFail($galleryId);

        // Handle the image upload
        if ($request->hasFile('upload_img')) {
            $image = $request->file('upload_img');
            $folderName = 'admin_gallery/' . $gallery->name;
            $path = $image->store($folderName, 'public');

            // Create a new GalleryImage record
            $galleryImg = GalleryImage::create([
                'gallery_id' => $gallery->id,
                'image_path' => $path,
                'title' => $request->input('title'),
                'description' => $request->input('description'),
            ]);

            Log::info('Gallery image created', ['galleryImg' => $galleryImg]);
        }

        return back()->with('success', 'Image uploaded successfully.');
    }

    public function destroy($id)
    {
        Log::info('Destroy image method called');

        // Find the image
        $image = GalleryImage::findOrFail($id);

        // Delete the image file from storage
        \Storage::disk('public')->delete($image->image_path);

        // Delete the image record from the database
        $image->delete();

        Log::info('Gallery image deleted', ['image' => $image]);

        return back()->with('success', 'Image deleted successfully.');
    }

    public function edit(Request $request, $id)
    {
        Log::info('Edit image method called');

        // Validate the request
        $validator = \Validator::make($request->all(), [
            'upload_img' => 'nullable|image|mimes:jpeg,png,jpg|max:4096',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Log::info('Validation passed');

        // Find the image
        $image = GalleryImage::findOrFail($id);

        // Handle the new image upload
        if ($request->hasFile('upload_img')) {
            // Delete the old image file from storage
            \Storage::disk('public')->delete($image->image_path);

            $newImage = $request->file('upload_img');
            $folderName = 'admin_gallery/' . $image->gallery->name;
            $path = $newImage->store($folderName, 'public');

            // Update the image path
            $image->image_path = $path;
        }

        // Update the title and description
        $image->title = $request->input('title');
        $image->description = $request->input('description');
        $image->save();

        Log::info('Gallery image updated', ['image' => $image]);

        return back()->with('success', 'Image updated successfully.');
    }
}
