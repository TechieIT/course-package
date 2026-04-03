<?php

use Illuminate\Support\Facades\Route;
use Techie\CourseModule\Http\Controllers\CourseController;
use Techie\CourseModule\Http\Controllers\FormAttributeController;
use Techie\CourseModule\Http\Controllers\FormController;

// Public course routes (site)
Route::middleware(['web'])
    ->group(function (): void {
        Route::get('courses', [CourseController::class, 'index'])
            ->name('course-module.public.courses.index');

        Route::get('course/{course:slug}', [CourseController::class, 'show'])
            ->name('course-module.public.courses.show');
    });

// Admin course-module routes (CMS)
Route::prefix(config('course-module.route_prefix', 'admin'))
    ->middleware(config('course-module.middleware', ['web', 'auth']))
    ->group(function (): void {
        Route::resource('courses', CourseController::class)->except(['index', 'show']);
        Route::get('courses', [CourseController::class, 'index'])
            ->name('course-module.courses.index');
        Route::get('course/{course}', [CourseController::class, 'show'])
            ->name('course-module.courses.show');

        Route::resource('forms', FormController::class);
        Route::resource('form-attributes', FormAttributeController::class);
        Route::post('form-attributes/reorder', [FormAttributeController::class, 'reorder'])
            ->name('form-attributes.reorder');
    });
