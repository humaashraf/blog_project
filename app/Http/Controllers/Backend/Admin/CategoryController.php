<?php

// namespace App\Http\Controllers;
namespace App\Http\Controllers\Backend\Admin;
use App\Http\Controllers\Controller;

use App\Models\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;


class CategoryController extends Controller
{
    public function __construct()
{
    $this->middleware('auth');
}

    public function index(Request $request)
    {



    //     dd(auth()->user()->getPermissionsViaRoles()->toArray(),
    //     auth()->user()->can('backend.admin.categories.delete'),
    //     $role->hasPermissionTo('backend.admin.categories.delete')
    // );

        if ($request->ajax()) {
            $role = auth()->user()->roles()->first();
            $categories = Category::select([
                'id',
                'name',
                'type',
            ]);


            return DataTables::of($categories)
            ->addColumn('action', function ($row) use($role) {
                $buttons = '';

                // First check if user is authenticated
                if (auth()->check()) {

                    // dd(auth()->user()->can('backend.admin.categories.edit'),auth()->user()->can('backend.admin.categories.edit'));
                    if ($role->hasPermissionTo('backend.admin.categories.edit')) {
                        $buttons .= '<a href="' . route('backend.admin.categories.edit', $row->id) . '" class="btn-sm btn btn-info editButton">Edit</a> ';
                    }
                    if ( $role->hasPermissionTo('backend.admin.categories.delete')) {
                        $buttons .= '<a href="' . route('backend.admin.categories.delete', $row->id) . '" class="btn-sm btn btn-danger delButton">Delete</a>';
                    }
                }




                return $buttons;
            })
            ->rawColumns(['action'])
                ->make(true);
        }

        return view('backend.admin.categories.create');

    }
    public function store(Request $request)
    {

        if($request->category_id != null){
            $category = Category::find($request->category_id);
            if(! $category){
                abort(404);
            }else{
                $category->update([
                    'name' => $request->name,
                    'type' => $request->type
                ]);
                return response()->json([
                    'success' => 'Categories Updated successfully!'
                ], 201);
            }

        }else{
            $request->validate([
                'name' => 'required|min:2|max:30',
                'type' => 'required',
            ]);

            Category::create([
                'name' => $request->name,
                'type' => $request->type
            ]);

            return response()->json([
                'success' => 'Categories Saved successfully!'
            ], 201);
        }

    }
    public function edit($id){
        $category = Category::find($id);
        if(! $category) {
            abort(404);
        }
        return $category;
        // return view('categories.edit', compact('category'));
    }
    public function delete($id){
        $category = Category::find($id);
        if(! $category) {
            abort(404);
        }
        $category->delete();
        return response()->json([
            'success' => 'Category Delete Successfully!'
        ], 201);
    }
}
