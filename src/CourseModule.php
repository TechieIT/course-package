<?php

namespace Techie\CourseModule;

use Illuminate\Database\Eloquent\Collection;
use Techie\CourseModule\Models\Course;

class CourseModule
{
    public function allPublished(): Collection
    {
        return Course::query()->published()->get();
    }
}
