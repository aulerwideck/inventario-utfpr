<?php

namespace App\Http\Controllers;

use App\Inventory;
use App\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $inventarios = Inventory::orderBy('id','desc')->get();
        return view('home')->with('inventarios', $inventarios);
    }

    public function roles()
    {
        /**
         * Criando os tipos de usuário
         */
//        $role = Role::create(['name' => 'administrator']);
//        $role = Role::create(['name' => 'president']);
//        $role = Role::create(['name' => 'reader']);

        /**
         * Configurando usuários e seus niveis de acesso
         */
//        $user = User::find(5);
//        $user->assignRole('administrator');
//        $user = User::find(6);
//        $user->assignRole('president');
//        $user = User::find(7);
//        $user->assignRole('reader');
//        $user = User::find(8);
//        $user->assignRole('reader');
//        $user = User::find(9);
//        $user->assignRole('reader');
//        $user = User::find(10);
//        $user->assignRole('reader');
//        $user = User::find(11);
//        $user->assignRole('reader');
//        $user = User::find(13);
//        $user->assignRole('reader');
//        $user = User::find(14);
//        $user->assignRole('reader');

        /**
         * Criando os permissões para inventários
         */
//        $permission = Permission::create(['name' => 'create inventories']);
//        $permission = Permission::create(['name' => 'read inventories']);
//        $permission = Permission::create(['name' => 'update inventories']);
//        $permission = Permission::create(['name' => 'delete inventories']);

        /**
         * Criando a permissão para visualizar relatorios
         */
//        $permission = Permission::create(['name' => 'see relatories']);

        /**
         * Criando os permissões para locais
         */
//        $permission = Permission::create(['name' => 'create locals']);
//        $permission = Permission::create(['name' => 'read locals']);
//        $permission = Permission::create(['name' => 'update locals']);
//        $permission = Permission::create(['name' => 'delete locals']);

        /**
         * Associando as permissões para as roles
         */
        $adm = Role::find(5);
//        $adm->givePermissionTo('create inventories');
//        $adm->givePermissionTo('read inventories');
//        $adm->givePermissionTo('update inventories');
//        $adm->givePermissionTo('delete inventories');
//        $adm->givePermissionTo('see relatories');
        $adm->givePermissionTo('create locals');
        $adm->givePermissionTo('read locals');
        $adm->givePermissionTo('update locals');
        $adm->givePermissionTo('delete locals');

    }
}
