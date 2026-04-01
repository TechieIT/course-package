<?php

namespace Techie\CourseModule\Support;

use Techie\CourseModule\Contracts\PluginInfo;

class CourseModulePluginInfo implements PluginInfo
{
    public function name(): string
    {
        return 'Course Module';
    }

    public function version(): string
    {
        return '1.0.0';
    }

    public function author(): string
    {
        return 'Techie';
    }

    public function description(): string
    {
        return 'Course, Form and FormAttribute plugin for TBC CMS.';
    }
}
