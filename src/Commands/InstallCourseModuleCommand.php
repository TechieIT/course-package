<?php

namespace Techie\CourseModule\Commands;

use Illuminate\Console\Command;

class InstallCourseModuleCommand extends Command
{
    protected $signature = 'course-module:install {--force : Overwrite published files}';

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

        $this->info('Course Module installed successfully.');

        return self::SUCCESS;
    }
}
