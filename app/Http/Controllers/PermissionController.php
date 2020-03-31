<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    private $sizePage = 10;

    /**
     * Display a listing of the resource.
     *
     * @param Permission $model
     * @return \Illuminate\View\View
     */
    public function index(Permission $model)
    {
        return view('permissions.index', ['permissions' => $model->paginate($this->sizePage)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Permission $model)
    {
        $model->create($request->all());

        return redirect()->route('permission.index')->withStatus(__('Permission successfully created'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return void
     */
    public function show($id)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Permission $permission
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Permission $permission)
    {
        return view('permissions.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Permission $permission
     * @return void
     */
    public function update(Request $request, Permission $permission)
    {
        $permission->update($request->all());

        return redirect()->route('permission.index')->withStatus(__('Permission successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();

        return redirect()->route('permission.index')->withStatus(__('Permission successfully deleted.'));
    }

    /**
     * Display a listing of the users
     *
     * @return \Illuminate\View\View
     */
    public function search()
    {
        $query = request('search_text');
        $model = Permission::where('name', 'LIKE', '%' . $query . '%');

        return view('permissions.index', ['permissions' => $model->paginate($this->sizePage)]);
    }
}
