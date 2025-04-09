<?php
namespace App\Http\Controllers\Backend\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;
// use DataTables;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $roles = Role::with('permissions')->get();
            return DataTables::of($roles)
                ->addColumn('permissions', function ($role) {
                    return implode(', ', $role->permissions->pluck('name')->toArray());
                })
                ->addColumn('actions', function ($role) {
                    return '
                        <button class="btn btn-info editRole btn-sm" data-id="'.$role->id.'">Edit</button>
                        <button class="btn btn-danger deleteRole btn-sm" data-id="'.$role->id.'">Delete</button>
                    ';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        $permissions = Permission::all();
        return view('backend.admin.roles.index', compact('permissions'));
    }


    public function store(Request $request)
{
    try {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'array|required'
        ]);

        $role = Role::create(['name' => $validated['name']]);
        $role->permissions()->sync($validated['permissions']); // Attach permissions

        return response()->json(['success' => 'Role created successfully!']);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}


    public function edit($id)
    {
        $role = Role::with('permissions')->find($id);
        return response()->json($role);
    }

    public function update(Request $request, $id)
{
    try {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name,'.$id,
            'permissions' => 'array|required'
        ]);

        $role = Role::findOrFail($id);
        // dd(auth()->user()->getPermissionsViaRoles()->toArray());
        // dd($validated['permissions']);
        // $role->update(['name' => $validated['name']]);
        // // Correct way to sync permissions
        // $role->permissions()->detach($role->getAllPermissions());
        // $role->permissions()->attach($validated['permissions']);
        $permissions = Permission::whereIn('id',$validated['permissions'])->get();
        $role->syncPermissions($permissions);

        return response()->json(['success' => 'Role updated successfully!']);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}


    public function destroy($id)
    {
        Role::find($id)->delete();
        return response()->json(['success' => 'Role deleted successfully.']);
    }
}
