<?php

namespace Techie\CourseModule\Support;

use Composer\InstalledVersions;
use Techie\CourseModule\Contracts\CmsModule;

class CourseModulePluginInfo implements CmsModule
{
    public function name(): string
    {
        return 'Course Module';
    }

    public function version(): string
    {
        $fromComposer = InstalledVersions::getPrettyVersion('techie/course-module');

        return $fromComposer ?? (string) config('course-module.version', 'dev');
    }

    public function author(): string
    {
        return 'Techie';
    }

    public function description(): string
    {
        return 'Courses, forms, and form attributes for your CMS — install via Composer without changing core CMS code.';
    }

    public function composerPackageName(): string
    {
        return 'techie/course-module';
    }

    public function routeNamePrefix(): string
    {
        return 'course-module.';
    }

    public function sidebarBlade(): string
    {
        return 'course-module::cms.sidebar';
    }

    public function permissionNames(): array
    {
        return CourseModulePermissions::all();
    }

    public function permissionGroups(): array
    {
        return CourseModulePermissions::groups();
    }
}
