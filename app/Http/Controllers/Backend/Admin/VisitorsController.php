<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Visitor;
use Yajra\DataTables\Facades\DataTables;

class VisitorsController extends Controller
{
    // public function index(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $visitors = Visitor::latest()->get();
    //         return DataTables::of($visitors)
    //             ->addIndexColumn()
    //             ->addColumn('action', function($row){
    //                 return '<button class="btn btn-danger btn-sm deleteBtn" data-id="'.$row->id.'">Delete</button>';
    //             })
    //             ->rawColumns(['action'])
    //             ->make(true);
    //     }
    //     return view('backend.admin.visitors.index');
    // }
    public function index(Request $request)
{
    if ($request->ajax()) {
        $visitors = Visitor::latest()->get();
        return DataTables::of($visitors)
            ->addIndexColumn()
            ->make(true);
    }
    return view('backend.admin.visitors.index');
}

    public function destroy($id)
    {
        Visitor::findOrFail($id)->delete();
        return response()->json(['success' => 'Visitor deleted successfully.']);
    }
}
