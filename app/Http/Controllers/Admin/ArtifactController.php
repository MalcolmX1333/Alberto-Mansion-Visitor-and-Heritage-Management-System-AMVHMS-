<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artifact;
use Illuminate\Http\Request;

class ArtifactController extends Controller
{
    public function index()
    {
        $artifacts = Artifact::orderByDesc('created_at')->get();
        return view('admin.artifact.index', compact('artifacts'));
    }

    public function show($id)
    {
        $artifact = Artifact::findOrFail($id);
        return response()->json(['success' => true, 'artifact' => $artifact]);
    }

    public function store(Request $request)
    {
        $artifact = Artifact::create($request->validate([
            'control_no' => 'required|string|max:255',
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|integer',
            'location' => 'required|string|max:255',
            'status' => 'required|string|max:255',
        ]));
        return response()->json(['success' => true, 'artifact' => $artifact]);
    }

    public function update(Request $request, $id)
    {
        $artifact = Artifact::findOrFail($id);
        $artifact->update($request->validate([
            'control_no' => 'required|string|max:255',
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|integer',
            'location' => 'required|string|max:255',
            'status' => 'required|string|max:255',
        ]));
        return response()->json(['success' => true, 'artifact' => $artifact]);
    }

    public function destroy($id)
    {
        Artifact::destroy($id);
        return response()->json(['success' => true]);
    }
}

