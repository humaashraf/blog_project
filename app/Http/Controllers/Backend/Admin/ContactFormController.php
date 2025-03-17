<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use Yajra\DataTables\Facades\DataTables;

class ContactFormController extends Controller
{
    public function index(Request $request)
{
    if ($request->ajax()) {
        $data = Contact::select(['id', 'name', 'email', 'phone', 'message', 'created_at']);
        return DataTables::of($data)
            ->addColumn('action', function($row){
                return '<button class="btn btn-danger deleteBtn" data-id="'.$row->id.'">Delete</button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    return view('backend.admin.contacts.indexform');
}
    // public function index(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $newsletters = Contact::latest()->get();
    //         return DataTables::of($newsletters)
    //             ->addIndexColumn()
    //             ->addColumn('action', function($row){
    //                 return '<button class="btn btn-danger btn-sm deleteBtn" data-id="'.$row->id.'">Delete</button>';
    //             })
    //             ->rawColumns(['action'])
    //             ->make(true);
    //     }
    //     return view('backend.admin.contacts.indexform');
    // }
    public function destroy($id)
    {
        Contact::findOrFail($id)->delete();
        return response()->json(['success' => 'Subscriber deleted successfully.']);
    }
}
