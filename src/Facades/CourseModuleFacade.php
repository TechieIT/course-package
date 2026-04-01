<?php

namespace Techie\CourseModule\Facades;

use Illuminate\Support\Facades\Facade;

class CourseModuleFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'course-module';
    }
}
