<?php

namespace Techie\CourseModule\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class CourseModulePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $slugs = [
            'courses',
            'course_forms',
            'form_attributes',
        ];

        $crudList = [
            'view',
            'create',
            'update',
            'delete',
        ];

        $createdPermissions = [];

        foreach ($slugs as $slug) {
            foreach ($crudList as $crud) {
                $permission = Permission::query()->firstOrCreate(
                    [
                        'name' => $crud . '-' . $slug,
                        'guard_name' => 'web',
                    ],
                    [
                        'name' => $crud . '-' . $slug,
                        'guard_name' => 'web',
                        'group' => ucfirst(str_replace('_', ' ', $slug)),
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
