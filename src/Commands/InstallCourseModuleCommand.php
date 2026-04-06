<?php

namespace Techie\CourseModule\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Techie\CourseModule\Database\Seeders\CourseModulePermissionSeeder;

class InstallCourseModuleCommand extends Command
{
    protected $signature = 'course-module:install
                            {--force : Overwrite published files}
                            {--skip-menu : Do not try to inject the sidebar include into the CMS layout}
                            {--skip-seed : Skip permission seeder}';

    protected $description = 'Publish assets, migrate, seed permissions, and optionally wire the CMS sidebar.';

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

        if (! $this->option('skip-seed')) {
            $this->info('Seeding course module permissions...');
            $this->call('db:seed', [
                '--class' => CourseModulePermissionSeeder::class,
            ]);
            if (class_exists(\Spatie\Permission\PermissionServiceProvider::class)) {
                $this->call('permission:cache-reset');
            }
        }

        $include = (string) config('course-module.cms.sidebar_include', 'course-module::cms.sidebar');
        $this->newLine();
        $this->comment('CMS wiring (recommended): add this line once in your admin sidebar layout:');
        $this->line("    @include('{$include}')");
        $this->newLine();

        if (! $this->option('skip-menu') && config('course-module.cms.install.inject_sidebar', true)) {
            $this->integrateSidebarMenu();
        }

        $this->info('Course Module installed successfully.');

        return self::SUCCESS;
    }

    private function integrateSidebarMenu(): void
    {
        $relative = (string) config('course-module.cms.install.sidebar_file', 'resources/views/admin/includes/header.blade.php');
        $sidebarPath = base_path($relative);

        if (! File::exists($sidebarPath)) {
            $this->warn("Sidebar file not found at [{$relative}]. Add the @include line manually (see above).");

            return;
        }

        $include = (string) config('course-module.cms.sidebar_include', 'course-module::cms.sidebar');
        $marker = "@include('{$include}')";

        $sidebarContent = File::get($sidebarPath);

        if (str_contains($sidebarContent, $marker)) {
            $this->info('Sidebar include already present.');

            return;
        }

        $insertBefore = (string) config(
            'course-module.cms.install.insert_before_needle',
            "                        @canany(['view-users', 'view-roles', 'view-team_members', 'view-member_departments'])"
        );

        $menuBlock = '                        ' . $marker . "\n\n";

        $normalized = str_replace("\r\n", "\n", $sidebarContent);
        $needle = str_replace("\r\n", "\n", $insertBefore);

        if (! str_contains($normalized, $needle)) {
            $this->warn('Sidebar anchor block not found in your layout. Add the @include line manually (see above).');

            return;
        }

        $count = 0;
        $updatedContent = str_replace($needle, $menuBlock . $needle, $normalized, $count);
        if ($count === 0) {
            $this->warn('Sidebar injection failed. Add the @include line manually (see above).');

            return;
        }

        File::put($sidebarPath, str_replace("\n", PHP_EOL, $updatedContent));

        $this->info('Sidebar include injected into ' . $relative . '.');
    }
}
