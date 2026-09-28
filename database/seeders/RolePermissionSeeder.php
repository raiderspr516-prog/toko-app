<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'products.view', 'products.create', 'products.update', 'products.delete',
            'categories.manage',
            'orders.view', 'orders.update-status',
            'payments.verify',
            'users.manage',
            'coupons.manage',
            'reports.view',
            'settings.manage',
        ];

        foreach ($permissions as $slug) {
            Permission::updateOrCreate(['slug' => $slug], ['name' => ucwords(str_replace(['.', '-'], ' ', $slug))]);
        }

        $manager = Role::updateOrCreate(
            ['slug' => 'store-manager'],
            ['name' => 'Store Manager', 'guard_name' => 'admin']
        );
        $manager->permissions()->sync(Permission::whereIn('slug', $permissions)->pluck('id'));
    }
}
