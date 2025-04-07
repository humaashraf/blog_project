<?php

namespace App\Http\Controllers\Backend\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // $data = User::with('roles')->get();
            $data = User::whereDoesntHave('roles', function ($query) {
                $query->whereIn('name', ['author']);
            })->with('roles')->get();

            return DataTables::of($data)
                ->addColumn('role_name', function ($row) {
                    return $row->roles->isNotEmpty() ? $row->roles->pluck('name')->implode(', ') : 'N/A';
                })
                ->addColumn('type', function ($row) {
                    return ucfirst($row->type); // Show type (Admin/User)
                })
                ->addColumn('action', function ($row) {
                    return '<a data-href="'.$row->id.'" class="btn-sm btn btn-info editButton">Edit</a>
                            <a data-href="'.$row->id.'" class="btn-sm btn btn-danger delButton">Delete</a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $roles = Role::pluck('name', 'id');
        return view('backend.admin.users.index', compact('roles'));
    }




public function store(Request $request)
{
    if ($request->subadmin_id) {
        $subadmin = User::find($request->subadmin_id);
        if (!$subadmin) {
            return response()->json([
                'error' => 'Subadmin not found!'
            ], 404);
        }

        // Password check added
        $password = $request->password ? bcrypt($request->password) : $subadmin->password;

        $subadmin->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $password,
            'role_id' => $request->role,
            'type' => 'admin',
        ]);

        $role = Role::find($request->role);
        $subadmin->syncRoles($role->id);

        return response()->json([
            'success' => 'Admin Updated successfully!'
        ], 201);
    }

    // Validation for new user
    $request->validate([
        'name' => 'required|min:2|max:30',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
        'role' => 'required|exists:roles,id',
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'role_id' => $request->role,
        'type' => 'admin',
    ]);

    $role = Role::find($request->role);
    $user->assignRole($role->id);

    return response()->json([
        'success' => 'Admin Saved successfully!'
    ], 201);
}


public function edit($id)
{
    if (!$id || !is_numeric($id)) {
        return response()->json(['error' => 'Invalid ID'], 400);
    }

    $admin = User::with('roles')->select(['id', 'name', 'email', 'type'])->find($id);
    if (!$admin) {
        return response()->json(['error' => 'Admin not found'], 404);
    }

    // Role ID ko retrieve karna
    $role_id = $admin->roles->isNotEmpty() ? $admin->roles->first()->id : null;

    return response()->json([
        'id' => $admin->id,
        'name' => $admin->name,
        'email' => $admin->email,
        'role_id' => $role_id, // Ensure role_id is included
        'type' => $admin->type,
    ]);
}


    public function delete($id)
    {
        $admin = User::find($id);

        if (!$admin) {
            return response()->json([
                'error' => 'Admin not found!'
            ], 404);
        }

        $admin->delete();

        return response()->json([
            'success' => 'Admin Deleted Successfully!'
        ], 200);
    }
}
