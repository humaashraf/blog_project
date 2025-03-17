<?php
namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SettingController extends Controller
{
    // Display the settings (index)
    public function index(Request $request)
    {
        $setting = Setting::first();

        return view('backend.admin.settings.index',compact('setting'));
    }


    public function getSettings()
{
    $settings = Setting::first(); // Pehla record fetch karega

    return response()->json($settings);
}

    public function edit($id)
{
    $setting = Setting::find($id);

    if (!$setting) {
        return response()->json(['error' => 'Setting not found!'], 404);

    }

    return response()->json($setting);

}
public function update(Request $request, $id)
{
    $setting = Setting::findOrFail($id);

    $request->validate([
        'blog_name' => 'required|string',
        'description' => 'nullable|string',
        'social_links' => 'nullable|string',
        'favicon_image' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        'logo_image' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
    ]);

    // Handle Favicon Upload
    if ($request->hasFile('favicon_image')) {
        if ($setting->favicon_image && file_exists(public_path('uploads/' . $setting->favicon_image))) {
            unlink(public_path('uploads/' . $setting->favicon_image));
        }
        $faviconName = time() . '_favicon.' . $request->file('favicon_image')->extension();
        $request->file('favicon_image')->move(public_path('uploads'), $faviconName);
        $setting->favicon_image = $faviconName;
    }

    // Handle Logo Upload
    if ($request->hasFile('logo_image')) {
        if ($setting->logo_image && file_exists(public_path('uploads/' . $setting->logo_image))) {
            unlink(public_path('uploads/' . $setting->logo_image));
        }
        $logoName = time() . '_logo.' . $request->file('logo_image')->extension();
        $request->file('logo_image')->move(public_path('uploads'), $logoName);
        $setting->logo_image = $logoName;
    }

    $setting->blog_name = $request->blog_name;
    $setting->description = $request->description;
    $setting->social_links = $request->social_links;
    $setting->save();

    return response()->json([
        'success' => 'Settings updated successfully!',
        'favicon_image' => asset('uploads/' . $setting->favicon_image) . '?t=' . time(),
        'logo_image' => asset('uploads/' . $setting->logo_image) . '?t=' . time(),
    ]);
}




}

