<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    private $sizePage = 10;

    /**
     * Display a listing of the resource.
     *
     * @param Role $model
     * @return \Illuminate\View\View
     */
    public function index(Role $model)
    {
        return view('roles.index', ['roles' => $model->paginate($this->sizePage)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('roles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Role $model)
    {
        $model->create($request->all());

        return redirect()->route('role.index')->withStatus(__('Role successfully created'));
    }

    /**
     * Display the specified resource.
     *
     * @param Role $role
     * @return void
     */
    public function show(Role $role)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Role $role
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Role $role)
    {
        $permissions = Permission::all();
        return view('roles.edit', compact(['role','permissions']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Role $role
     * @return void
     */
    public function update(Request $request, Role $role)
    {
        $role->update($request->all());

        return redirect()->route('role.index')->withStatus(__('Role successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()->route('role.index')->withStatus(__('Role successfully deleted.'));
    }

    /**
     * Associate permissions to role.
     *
     * @param \Illuminate\Http\Request $request
     * @param Role $role
     * @return void
     */
    public function connect(Request $request, Role $role)
    {
        $permissions = Permission::all();
        $role->revokePermissionTo($permissions);
        foreach ($permissions as $permission)
        {
            if($request->get(str_replace(' ', '_', $permission->name)))
            {
                $role->givePermissionTo($permission);
            }
        }

        return redirect()->route('role.index')->withStatus(__('Role successfully updated.'));
    }
}
