<?php

namespace Techie\CourseModule\Contracts;

interface CmsModule extends PluginInfo
{
    public function composerPackageName(): string;

    public function routeNamePrefix(): string;

    public function sidebarBlade(): string;

    /**
     * @return list<string>
     */
    public function permissionNames(): array;

    /**
     * @return array<string, list<string>>
     */
    public function permissionGroups(): array;
}
