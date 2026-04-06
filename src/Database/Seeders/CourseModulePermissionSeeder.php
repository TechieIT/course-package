<?php

namespace Techie\CourseModule\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Techie\CourseModule\Support\CourseModulePermissions;

class CourseModulePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $groups = CourseModulePermissions::groups();
        $createdPermissions = [];

        foreach ($groups as $label => $names) {
            foreach ($names as $name) {
                $permission = Permission::query()->firstOrCreate(
                    [
                        'name' => $name,
                        'guard_name' => 'web',
                    ],
                    [
                        'name' => $name,
                        'guard_name' => 'web',
                        'group' => $label,
                    ]
                );

                $createdPermissions[] = $permission->name;
            }
        }

        $adminRole = Role::query()->where('name', 'admin')->first();
        if ($adminRole) {
            $adminRole->givePermissionTo(array_unique($createdPermissions));
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
