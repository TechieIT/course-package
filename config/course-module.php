<?php

return [
    /*
    | Set false to disable package HTTP routes (models, views, and migrations still load).
    */
    'enabled' => env('COURSE_MODULE_ENABLED', true),

    /*
    | Fallback when the package is not installed via Composer (e.g. path repo without lock entry).
    */
    'version' => null,

    'table_prefix' => 'techie_',
    'thumbnail_disk' => 'public',
    'thumbnail_path' => 'courses/thumbnails',
    'middleware' => ['web', 'auth'],
    'route_prefix' => 'admin',
    'use_cms_layout' => true,
    'layout' => 'layouts.admin',

    /*
    | CMS integration: keep your CMS repo unchanged and wire the module from here.
    | Preferred: add @include(config('course-module.cms.sidebar_include')) in your admin sidebar once.
    */
    'cms' => [
        'sidebar_include' => 'course-module::cms.sidebar',
        'install' => [
            'inject_sidebar' => true,
            'sidebar_file' => 'resources/views/admin/includes/header.blade.php',
            'insert_before_needle' => "                        @canany(['view-users', 'view-roles', 'view-team_members', 'view-member_departments'])",
        ],
    ],
];
