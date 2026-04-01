<?php

namespace Techie\CourseModule\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class CourseModulePermissionSeeder extends Seeder
{
    public function run(): void
    {
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

        foreach ($slugs as $slug) {
            foreach ($crudList as $crud) {
                Permission::query()->firstOrCreate(
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
            }
        }
    }
}
