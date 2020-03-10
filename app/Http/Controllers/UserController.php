<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    private $sizePage = 10;

    /**
     * Display a listing of the users
     *
     * @param \App\User $model
     * @return \Illuminate\View\View|void
     */
    public function index(User $model)
    {
        if (Auth::user()->can('update users')) {
            return view('users.index', ['users' => $model->paginate($this->sizePage)]);
        } else {
            return abort(401);
        }
    }

    /**
     * Show the form for creating a new user
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        if (Auth::user()->can('update users')) {
            return view('users.create');
        } else {
            return abort(401);
        }
    }

    /**
     * Store a newly created user in storage
     *
     * @param \App\Http\Requests\UserRequest $request
     * @param \App\User $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UserRequest $request, User $model)
    {
        $model->create($request->merge(['password' => Hash::make($request->get('password'))])->all());

        return redirect()->route('user.index')->withStatus(__('User successfully created.'));
    }

    /**
     * Show the form for editing the specified user
     *
     * @param \App\User $user
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        if (Auth::user()->can('update users')) {
            $roles = Role::all();
            return view('users.edit', compact(['user', 'roles']));
        } else {
            return abort(401);
        }
    }

    /**
     * Update the specified user in storage
     *
     * @param \App\Http\Requests\UserRequest $request
     * @param \App\User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UserRequest $request, User $user)
    {
        $hasPassword = $request->get('password');
        $user->update(
            $request
                ->merge(
                    ['password' => Hash::make($request->get('password'))]
                )
                ->except(
                    [
                        $hasPassword ? '' : 'password',
                        'role'
                    ]
                )
        );

        $user->roles()->detach();
        if ($request->get('role')) {
            $role_id = $request->get('role');
            $role = Role::findById($role_id);
            $user->assignRole($role);
        }

        return redirect()->route('user.index')->withStatus(__('User successfully updated.'));
    }

    /**
     * Remove the specified user from storage
     *
     * @param \App\User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        if (Auth::user()->can('update users')) {
            $user->delete();
            return redirect()->route('user.index')->withStatus(__('User successfully deleted.'));
        } else {
            return abort(401);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\User $user
     * @return void
     */
    public function show(User $user)
    {
        return abort(404);
    }

    /**
     * Display a listing of the users
     *
     * @param \App\User $model
     * @return \Illuminate\View\View
     */
    public function search()
    {
        $query = request('search_text');
        $model = User::where('name', 'LIKE', '%' . $query . '%')->orWhere('email', 'LIKE', '%' . $query . '%');

        return view('users.index', ['users' => $model->paginate($this->sizePage)]);
    }
}
