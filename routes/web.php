<?php

use Illuminate\Support\Facades\Route;
use Techie\CourseModule\Http\Controllers\CourseController;
use Techie\CourseModule\Http\Controllers\FormAttributeController;
use Techie\CourseModule\Http\Controllers\FormController;

Route::prefix(config('course-module.route_prefix', 'admin'))
    ->group(function (): void {
        Route::resource('courses', CourseController::class);
        Route::resource('forms', FormController::class);
        Route::resource('form-attributes', FormAttributeController::class);
        Route::post('form-attributes/reorder', [FormAttributeController::class, 'reorder'])
            ->name('form-attributes.reorder');
    });
