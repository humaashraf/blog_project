<?php

// namespace App\Http\Controllers;
namespace App\Http\Controllers\Backend\Admin;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SubAdminController extends Controller
{

//     public function index(Request $request)
// {
//     if ($request->ajax()) {
//         $subadmins = User::select([
//             'id',
//             'role_id',
//             'name',
//             'email',
//             'password'
//         ])->where('role_id', 1); // Only fetch users with role_id = 1

//         return DataTables::of($subadmins)
//             ->addColumn('action', function($row){
//                 return '<a data-href="'.$row->id.'" class="btn-sm btn btn-info editButton">Edit</a>
//                         <a data-href="'.$row->id.'" class="btn-sm btn btn-danger delButton">Delete</a>';
//             })
//             ->rawColumns(['action'])
//             ->make(true);
//     }

//     return view('backend.admin.sub_admins.index');
// }
public function index(Request $request)
{
    if ($request->ajax()) {
        // $data = User::with('role')->get(); // Join to Role table
        $data = User::with('role')
            ->where('role_id', 1) // ✅ Sirf role_id = 1 waly show honge
            ->get();

        return DataTables::of($data)
            ->addColumn('role_name', function ($row) {
                return $row->role ? $row->role->name : 'N/A'; // Role name fetch
            })
            ->addColumn('action', function ($row) {
                return '<button class="btn btn-sm btn-warning editButton" data-href="' . $row->id . '">Edit</button>
                        <button class="btn btn-sm btn-danger delButton" data-href="' . $row->id . '">Delete</button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    return view('backend.admin.sub_admins.index');
}


    public function store(Request $request)
{
    if($request->subadmin_id != null){
        $subadmin = User::find($request->subadmin_id);
        if(! $subadmin){
            abort(404);
        } else {
            $subadmin->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role_id' => 1
            ]);
            return response()->json([
                'success' => 'Sub Admin Updated successfully!'
            ], 201);
        }
    } else {
        $request->validate([
            'name' => 'required|min:2|max:30',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_id' => 1
        ]);

        return response()->json([
            'success' => 'Sub Admin Saved successfully!'
        ], 201);
    }
}
public function edit($id)
{
    $subadmin = User::select(['id', 'role_id', 'name', 'email', 'password'])->where('role_id', 1)->find($id);
    if(!$subadmin) {
        abort(404);
    }
    return response()->json($subadmin);
}

public function delete($id)
{
    $subadmin = User::where('role_id', 1)->find($id);
    if(!$subadmin) {
        abort(404);
    }
    $subadmin->delete();
    return response()->json([
        'success' => 'Sub Admin Deleted Successfully!'
    ], 201);
}

}
