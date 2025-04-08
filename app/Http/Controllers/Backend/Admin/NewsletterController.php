<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Newsletter;
use Yajra\DataTables\Facades\DataTables;

class NewsletterController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $role = auth()->user()->roles()->first(); // Get the user's role
            $newsletters = Newsletter::latest()->get();

            return DataTables::of($newsletters)
                ->addIndexColumn()
                ->addColumn('action', function($row) use ($role) {
                    $buttons = '';

                    // Only show delete button if user has delete permission
                    if (auth()->check() && $role->hasPermissionTo('backend.admin.newsletters.destroy')) {
                        $buttons .= '<button class="btn btn-danger btn-sm deleteBtn" data-id="' . $row->id . '">Delete</button>';
                    }

                    return $buttons;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('backend.admin.newsletters.index');
    }

    public function destroy($id)
    {
        Newsletter::findOrFail($id)->delete();
        return response()->json(['success' => 'Subscriber deleted successfully.']);
    }
}
