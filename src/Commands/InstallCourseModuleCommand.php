<?php

namespace Techie\CourseModule\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Techie\CourseModule\Database\Seeders\CourseModulePermissionSeeder;

class InstallCourseModuleCommand extends Command
{
    protected $signature = 'course-module:install {--force : Overwrite published files} {--skip-menu : Skip admin sidebar integration}';

    protected $description = 'Install the Course Module package.';

    public function handle(): int
    {
        $this->info('Publishing course-module config...');
        $this->call('vendor:publish', [
            '--tag' => 'course-module-config',
            '--force' => (bool) $this->option('force'),
        ]);

        $this->info('Publishing course-module views...');
        $this->call('vendor:publish', [
            '--tag' => 'course-module-views',
            '--force' => (bool) $this->option('force'),
        ]);

        $this->info('Publishing course-module migrations...');
        $this->call('vendor:publish', [
            '--tag' => 'course-module-migrations',
            '--force' => (bool) $this->option('force'),
        ]);

        $this->info('Running migrations...');
        $this->call('migrate');

        $this->info('Seeding course module permissions...');
        $this->call('db:seed', [
            '--class' => CourseModulePermissionSeeder::class,
        ]);
        $this->call('permission:cache-reset');

        if (! $this->option('skip-menu')) {
            $this->integrateSidebarMenu();
        }

        $this->info('Course Module installed successfully.');

        return self::SUCCESS;
    }

    private function integrateSidebarMenu(): void
    {
        $sidebarPath = base_path('resources/views/admin/includes/header.blade.php');

        if (! File::exists($sidebarPath)) {
            $this->warn('Sidebar file not found. Skipping sidebar integration.');
            return;
        }

        $sidebarContent = File::get($sidebarPath);
        $marker = "@canany(['view-courses', 'view-course_forms', 'view-form_attributes'])";

        if (str_contains($sidebarContent, $marker)) {
            $this->info('Sidebar integration already exists.');
            return;
        }

        $insertBefore = "                        @canany(['view-users', 'view-roles', 'view-team_members', 'view-member_departments'])";
        $menuBlock = <<<'BLADE'
                        @canany(['view-courses', 'view-course_forms', 'view-form_attributes'])
                            <li class="nav-label">Course Module</li>
                            @include('components.admin.menus.menu-item', ['route' => route('course-module.courses.index'), 'title' => 'Courses', 'icon' => 'fad fa-graduation-cap', 'permission' => 'view-courses', 'active' => request()->routeIs('course-module.courses.*')])
                            @include('components.admin.menus.menu-item', ['route' => route('course-module.forms.index'), 'title' => 'Course Forms', 'icon' => 'fad fa-file-contract', 'permission' => 'view-course_forms', 'active' => request()->routeIs('course-module.forms.*')])
                            @include('components.admin.menus.menu-item', ['route' => route('course-module.form-attributes.index'), 'title' => 'Form Attributes', 'icon' => 'fad fa-list-ol', 'permission' => 'view-form_attributes', 'active' => request()->routeIs('course-module.form-attributes.*')])
                        @endcanany

BLADE;

        $normalized = str_replace("\r\n", "\n", $sidebarContent);
        $needle = str_replace("\r\n", "\n", $insertBefore);

        if (! str_contains($normalized, $needle)) {
            $this->warn('Sidebar anchor block not found. Skipping sidebar integration.');
            return;
        }

        $count = 0;
        $updatedContent = str_replace($needle, $menuBlock . $needle, $normalized, $count);
        if ($count === 0) {
            $this->warn('Sidebar injection failed (no replacement).');
            return;
        }

        File::put($sidebarPath, str_replace("\n", PHP_EOL, $updatedContent));

        $this->info('Course Module sidebar menu injected.');
    }
}
