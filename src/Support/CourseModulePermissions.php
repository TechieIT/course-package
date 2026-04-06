<?php

namespace Techie\CourseModule\Support;

final class CourseModulePermissions
{
    /**
     * @return list<string>
     */
    public static function all(): array
    {
        $slugs = ['courses', 'course_forms', 'form_attributes'];
        $crud = ['view', 'create', 'update', 'delete'];
        $names = [];
        foreach ($slugs as $slug) {
            foreach ($crud as $op) {
                $names[] = $op . '-' . $slug;
            }
        }

        return $names;
    }

    /**
     * @return array<string, list<string>>
     */
    public static function groups(): array
    {
        $slugs = ['courses', 'course_forms', 'form_attributes'];
        $crud = ['view', 'create', 'update', 'delete'];
        $groups = [];
        foreach ($slugs as $slug) {
            $label = ucfirst(str_replace('_', ' ', $slug));
            $groups[$label] = [];
            foreach ($crud as $op) {
                $groups[$label][] = $op . '-' . $slug;
            }
        }

        return $groups;
    }
}
