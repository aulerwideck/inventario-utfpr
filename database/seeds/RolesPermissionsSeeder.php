<?php

use Illuminate\Database\Seeder;

class RolesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Spatie\Permission\Models\Permission::create(['name' => 'create inventories']);
        \Spatie\Permission\Models\Permission::create(['name' => 'read inventories']);
        \Spatie\Permission\Models\Permission::create(['name' => 'update inventories']);
        \Spatie\Permission\Models\Permission::create(['name' => 'delete inventories']);
        \Spatie\Permission\Models\Permission::create(['name' => 'create locals']);
        \Spatie\Permission\Models\Permission::create(['name' => 'read locals']);
        \Spatie\Permission\Models\Permission::create(['name' => 'update locals']);
        \Spatie\Permission\Models\Permission::create(['name' => 'delete locals']);
        \Spatie\Permission\Models\Permission::create(['name' => 'see relatories']);
        \Spatie\Permission\Models\Permission::create(['name' => 'archive collect']);

        \Spatie\Permission\Models\Role::create(['name' => 'Super Admin']);
        $admin = \Spatie\Permission\Models\Role::create(['name' => 'Admin']);
        $president = \Spatie\Permission\Models\Role::create(['name' => 'President']);
        $reader = \Spatie\Permission\Models\Role::create(['name' => 'Reader']);

        $admin->givePermissionTo('create inventories');
        $admin->givePermissionTo('read inventories');
        $admin->givePermissionTo('update inventories');
        $admin->givePermissionTo('delete inventories');
        $admin->givePermissionTo('create locals');
        $admin->givePermissionTo('read locals');
        $admin->givePermissionTo('update locals');
        $admin->givePermissionTo('delete locals');
        $admin->givePermissionTo('see relatories');
        $admin->givePermissionTo('archive collect');

        $president->givePermissionTo('read inventories');
        $president->givePermissionTo('update inventories');
        $president->givePermissionTo('create locals');
        $president->givePermissionTo('read locals');
        $president->givePermissionTo('update locals');
        $president->givePermissionTo('delete locals');
        $president->givePermissionTo('see relatories');
        $president->givePermissionTo('archive collect');

        $reader->givePermissionTo('read inventories');
        $reader->givePermissionTo('read locals');
    }
}
