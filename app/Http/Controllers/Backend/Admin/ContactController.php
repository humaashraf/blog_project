<?php

namespace App\Http\Controllers\Backend\Admin;
use App\Models\ContactSlider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ContactController extends Controller
{
    // public function index(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $sliders = ContactSlider::select([
    //             'id',
    //             'slider_image',
    //             'sort_by', // Added new column
    //         ])->orderBy('sort_by', 'desc'); // Sorting by sort_by column

    //         return DataTables::of($sliders)
    //             ->addColumn('image', fn($row) => $row->slider_image ? '<img src="' . asset('uploads/' . $row->slider_image) . '" width="50" height="50">' : 'No Image')
    //             ->addColumn('action', function($row){
    //                 return '<a data-href="'.$row->id.'" class="btn-sm btn btn-info editButton">Edit</a>
    //                         <a href="javascript:void(0);" data-id="'.$row->id.'" class="btn-sm btn btn-danger delButton">Delete</a>';
    //             })
    //             ->rawColumns(['action', 'image'])
    //             ->make(true);
    //     }

    //     return view('backend.admin.contacts.create');
    // }
    public function index(Request $request)
{
    if ($request->ajax()) {
        $role = auth()->user()->roles()->first(); // Get current user's role

        $sliders = ContactSlider::select([
            'id',
            'slider_image',
            'sort_by',
        ])->orderBy('sort_by', 'desc');

        return DataTables::of($sliders)
            ->addColumn('image', fn($row) => $row->slider_image ? '<img src="' . asset('uploads/' . $row->slider_image) . '" width="50" height="50">' : 'No Image')
            ->addColumn('action', function ($row) use ($role) {
                $buttons = '';

                if (auth()->check()) {
                    if ($role->hasPermissionTo('backend.admin.contacts.edit')) {
                        $buttons .= '<a data-href="' . $row->id . '" class="btn-sm btn btn-info editButton">Edit</a> ';
                    }

                    if ($role->hasPermissionTo('backend.admin.contacts.delete')) {
                        $buttons .= '<a href="javascript:void(0);" data-id="' . $row->id . '" class="btn-sm btn btn-danger delButton">Delete</a>';
                    }
                }

                return $buttons;
            })
            ->rawColumns(['action', 'image'])
            ->make(true);
    }

    return view('backend.admin.contacts.create');
}


    public function store(Request $request)
    {
        $request->validate([
            'slider_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sort_by' => 'nullable|integer', // Validation for sort_by
        ]);

        if ($request->hasFile('slider_image')) {
            $file = $request->file('slider_image');
            $filename = time() . '.' . $file->getClientOriginalExtension();

            // Move the file to the 'uploads' folder in the public directory
            $file->move(public_path('uploads'), $filename);

            // Save the file name to the database
            $slider = new ContactSlider();
            $slider->slider_image = $filename;
            $slider->sort_by = $request->sort_by ?? 0; // Default value if not provided
            $slider->save();

            return response()->json(['success' => 'Image uploaded successfully!']);
        }

        return response()->json(['error' => 'No file selected.']);
    }

    public function update(Request $request, $id)
{
    $slider = ContactSlider::find($id);
    if (!$slider) {
        return response()->json(['error' => 'Slider not found.'], 404);
    }

    $request->validate([
        'sort_by' => 'required|integer',
    ]);

    $slider->sort_by = $request->sort_by;
    $slider->save();

    return response()->json([
        'success' => 'Slider updated successfully!',
    ], 200);
}

    public function edit($id){
        $slider = ContactSlider::find($id);
        if(! $slider) {
            abort(404);
        }
        return $slider;
    }

    public function delete($id)
    {
        // Find the slider by ID
        $slider = ContactSlider::find($id);

        // Check if the slider exists
        if ($slider) {
            // Check if the image exists and delete it
            if ($slider->slider_image && file_exists(public_path('uploads/' . $slider->slider_image))) {
                unlink(public_path('uploads/' . $slider->slider_image)); // Delete the image
            }

            // Delete the slider record from the database
            $slider->delete();

            // Return success response
            return response()->json(['success' => 'Slider Image Deleted Successfully!'], 200);
        }

        // If slider not found, return error response
        return response()->json(['error' => 'Slider Image not found.'], 404);
    }
}
