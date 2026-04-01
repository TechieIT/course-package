<?php

namespace Techie\CourseModule\Contracts;

interface PluginInfo
{
    public function name(): string;

    public function version(): string;

    public function author(): string;

    public function description(): string;
}
