<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use App\Models\User;
use App\Models\Admin;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    
    public function run()
    {
        // Role::truncate();
        // Permission::truncate();

        // Create Roles
        $roleSuperAdmin = Role::create(['guard_name' => 'admin','name' => 'superadmin']);
        $roleAdmin = Role::create(['guard_name' => 'admin','name' => 'cashier']);
        $roleEditor = Role::create(['guard_name' => 'admin','name' => 'mechanic']);
        // $roleUser = Role::create(['guard_name' => 'admin','name' => 'user']);

        // Permission List as array
        $permissions = [
            // [
            //     'group_name' => 'dashboard',
            //     'permissions' => [
            //         'dashboard.view',
            //         'dashboard.color',
            //     ],
            // ],
            [
                'group_name' => 'order',
                'permissions' => [
                    'order.create',
                    'order.view',
                    'order.edit',
                    'order.delete',
                    'order.receipt',
                    'order.history',
                ],
            ],
            [
                'group_name' => 'customer',
                'permissions' => [
                    'customer.create',
                    'customer.view',
                    'customer.edit',
                    'customer.delete',
                    'customer.history',
                ],
            ],
            [
                'group_name' => 'vehicle',
                'permissions' => [
                    'vehicle.create',
                    'vehicle.view',
                    'vehicle.edit',
                    'vehicle.delete',
                    'vehicle.history',
                ],
            ],
            [
                'group_name' => 'product',
                'permissions' => [
                    'product.create',
                    'product.view',
                    'product.edit',
                    'product.delete',
                    'product.history',
                ],
            ],
            [
                'group_name' => 'payment',
                'permissions' => [
                    'payment.create',
                    'payment.view',
                    'payment.edit',
                    'payment.delete',
                    'payment.history',
                ],
            ],
            [
                'group_name' => 'paymentType',
                'permissions' => [
                    'paymentType.create',
                    'paymentType.view',
                    'paymentType.edit',
                    'paymentType.delete',
                    'paymentType.history',
                ],
            ],
            [
                'group_name' => 'ipallowlist',
                'permissions' => [
                    'ipallowlist.create',
                    'ipallowlist.view',
                    'ipallowlist.edit',
                    'ipallowlist.delete',
                    'ipallowlist.history',
                ],
            ],
            [
                'group_name' => 'notification',
                'permissions' => [
                    'notification.create',
                    'notification.view',
                    'notification.delete',
                ],
            ],
            [
                'group_name' => 'account',
                'permissions' => [
                    'account.create',
                    'account.view',
                    'account.edit',
                    'account.delete',
                    'account.history',
                ],
            ],
            [
                'group_name' => 'roles',
                'permissions' => [
                    'roles.create',
                    'roles.view',
                    'roles.edit',
                    'roles.delete',
                ],
            ],
            // [
            //     'group_name' => 'permissions',
            //     'permissions' => [
            //         'permissions.create',
            //         'permissions.view',
            //         'permissions.edit',
            //         'permissions.delete',
            //     ],
            // ],
            [
                'group_name' => 'profile',
                'permissions' => [
                    'profile.view',
                    'profile.edit-info',
                    'profile.edit-password',
                    'profile.edit-image',
                ],
            ]
        ];

        // Create and Assign Permissions
        // admins
        foreach ($permissions as $group) {
            foreach ($group['permissions'] as $perm) {
                $permission = Permission::create(['name' => $perm, 'group_name' => $group['group_name'], 'guard_name' => 'admin']);
                $roleSuperAdmin->givePermissionTo($permission);
            }
        }

        $super_admin = Admin::where('email', 'admin@mail.com')->first();
        $super_admin->assignRole($roleSuperAdmin);
        // admins
    }
}
