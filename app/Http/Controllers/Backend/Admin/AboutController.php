<?php
namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\About;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AboutController extends Controller
{
    // Display the settings (index)
    public function index(Request $request)
    {
        $about = About::first();

        return view('backend.admin.abouts.index',compact('about'));
    }


    public function getaboutSettings()
{
    $abouts = About::first(); // Pehla record fetch karega

    return response()->json($abouts);
}

    public function edit($id)
{
    $about = About::find($id);

    if (!$about) {
        return response()->json(['error' => 'Setting not found!'], 404);

    }

    return response()->json($about);

}
public function update(Request $request, $id)
{
    $about = About::findOrFail($id);

    $request->validate([
        'name' => 'required|string',
        'description' => 'nullable|string',
        'image' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
    ]);

    // Handle About us image Upload
    if ($request->hasFile('image')) {
        if ($about->image && file_exists(public_path('uploads/' . $about->image))) {
            unlink(public_path('uploads/' . $about->image));
        }
        $imageName = time() . '_image.' . $request->file('image')->extension();
        $request->file('image')->move(public_path('uploads'), $imageName);
        $about->image = $imageName;
    }

    $about->name = $request->name;
    $about->description = $request->description;
    $about->save();

    return response()->json([
        'success' => 'About Settings updated successfully!',
        'image' => asset('uploads/' . $about->image) . '?t=' . time(),
    ]);
}




}

