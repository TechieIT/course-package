<?php

namespace Techie\CourseModule;

use Illuminate\Database\Eloquent\Collection;
use Techie\CourseModule\Contracts\CmsModule;
use Techie\CourseModule\Models\Course;

class CourseModule
{
    public function manifest(): CmsModule
    {
        return app(CmsModule::class);
    }

    public function allPublished(): Collection
    {
        return Course::query()->published()->get();
    }
}
