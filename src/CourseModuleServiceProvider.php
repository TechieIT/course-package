<?php

namespace Techie\CourseModule;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Techie\CourseModule\Commands\InstallCourseModuleCommand;
use Techie\CourseModule\Contracts\PluginInfo;
use Techie\CourseModule\Support\CourseModulePluginInfo;

class CourseModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/course-module.php', 'course-module');

        $this->app->singleton('course-module', fn (): CourseModule => new CourseModule());
        $this->app->singleton(PluginInfo::class, CourseModulePluginInfo::class);
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'course-module');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        Route::middleware(config('course-module.middleware', ['web', 'auth']))
            ->as('course-module.')
            ->group(__DIR__ . '/../routes/web.php');

        $this->publishes([
            __DIR__ . '/../config/course-module.php' => config_path('course-module.php'),
        ], 'course-module-config');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/course-module'),
        ], 'course-module-views');

        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'course-module-migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCourseModuleCommand::class,
            ]);
        }
    }
}
