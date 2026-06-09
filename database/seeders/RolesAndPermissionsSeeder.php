<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'manage-products', 'manage-categories', 'manage-orders', 'manage-subscriptions',
            'manage-users', 'manage-consultancy', 'manage-blog', 'manage-settings',
            'view-analytics', 'manage-coupons', 'manage-deliveries',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $roles = [
            'super-admin' => $permissions,
            'admin' => ['manage-products','manage-categories','manage-orders','manage-subscriptions','manage-blog','manage-coupons','view-analytics'],
            'manager' => ['manage-orders','manage-subscriptions','view-analytics'],
            'delivery-agent' => ['manage-deliveries'],
            'customer' => [],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($rolePermissions);
        }
    }
}
