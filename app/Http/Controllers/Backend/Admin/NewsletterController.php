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
            $newsletters = Newsletter::latest()->get();
            return DataTables::of($newsletters)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    return '<button class="btn btn-danger btn-sm deleteBtn" data-id="'.$row->id.'">Delete</button>';
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
