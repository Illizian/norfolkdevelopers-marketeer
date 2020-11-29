<?php

namespace Database\Seeders;

use \Illuminate\Database\Seeder;
use \Spatie\Permission\Models\Role;
use \Spatie\Permission\Models\Permission;
use \Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /*
     * An array of the Models you wish to create permissions for
     *
     * @var array
     */
    protected $models = [
        'event',
        'scheduled-notification',
        'template',
        'template-notification',
        'user',
    ];

    /*
     * An array of the actions available on the Models you wish to create permissions for
     *
     * @var array
     */
    protected $modelActions = [
        'create',
        'read',
        'update',
        'delete',
    ];

    /*
     * A matrix of Roles with their associated $models-$modelActions permissions,
     * - Using the $models & $modelAction arrays to create a matrix of role to permission
     *
     * @var array
     */
    protected $matrix = [
        'editor' => [ 0,1,1,0,  1,1,1,1,  0,1,1,0,  1,1,1,1,  0,0,0,0 ],
        'admin' =>  [ 1,1,1,1,  1,1,1,1,  1,1,1,1,  1,1,1,1,  1,1,1,1 ],
    ];

    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create General Permissions
        Permission::create([
            'name' => 'tool.nova',
        ]);

        // Create Permissions from $models & $modelActions
        foreach ($this->models as $model) {
            foreach ($this->modelActions as $action) {
                Permission::create([
                    'name' => "model.$model.$action",
                ]);
            }
        }

        // Using $models and $modelActions as the key, create Roles from $matrix
        foreach ($this->matrix as $role => $permissions) {
            $role = Role::create([ 'name' => $role ]);

            foreach ($permissions as $index => $value) {
                if ($value === 1) {
                    $model = $this->models[floor($index / (count($this->models) - 1))];
                    $action = $this->modelActions[$index % count($this->modelActions)];

                    $role->givePermissionTo("model.$model.$action");

                    // Add General Permissions to this $role
                    $role->givePermissionTo('tool.nova');
                }
            }
        }
    }
}
