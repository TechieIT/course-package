<?php

return [
    'table_prefix' => 'techie_',
    'thumbnail_disk' => 'public',
    'thumbnail_path' => 'courses/thumbnails',
    'middleware' => ['web', 'auth'],
    'route_prefix' => 'admin',
    'use_cms_layout' => true,
    // Render course-module pages inside the main CMS admin layout
    'layout' => 'layouts.admin',
];
