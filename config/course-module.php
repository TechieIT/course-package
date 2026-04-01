<?php

return [
    'table_prefix' => '',
    'thumbnail_disk' => 'public',
    'thumbnail_path' => 'courses/thumbnails',
    'middleware' => ['web', 'auth'],
    'route_prefix' => 'admin',
    'use_cms_layout' => true,
    'layout' => 'layouts.app',
];
