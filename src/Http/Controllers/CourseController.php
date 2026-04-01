<?php

namespace Techie\CourseModule\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Techie\CourseModule\Enums\CourseStatus;
use Techie\CourseModule\Http\Requests\StoreCourseRequest;
use Techie\CourseModule\Http\Requests\UpdateCourseRequest;
use Techie\CourseModule\Models\Course;
use Techie\CourseModule\Models\Form;
use Techie\CourseModule\Services\CourseService;

class CourseController extends Controller
{
    public function __construct(private readonly CourseService $courseService)
    {
    }

    public function index(): View
    {
        $courses = Course::query()->with('form')->latest()->paginate(15);

        return view('course-module::courses.index', compact('courses'));
    }

    public function create(): View
    {
        $forms = Form::query()->orderBy('name')->get();
        $statuses = CourseStatus::cases();

        return view('course-module::courses.create', compact('forms', 'statuses'));
    }

    public function store(StoreCourseRequest $request): RedirectResponse
    {
        $this->courseService->createCourse($request->validated());

        return redirect()->route('course-module.courses.index')->with('success', 'Course created successfully.');
    }

    public function show(Course $course): View
    {
        $course->load(['form', 'formAttributes']);

        return view('course-module::courses.show', compact('course'));
    }

    public function edit(Course $course): View
    {
        $forms = Form::query()->orderBy('name')->get();
        $statuses = CourseStatus::cases();

        return view('course-module::courses.edit', compact('course', 'forms', 'statuses'));
    }

    public function update(UpdateCourseRequest $request, Course $course): RedirectResponse
    {
        $this->courseService->updateCourse($course, $request->validated());

        return redirect()->route('course-module.courses.index')->with('success', 'Course updated successfully.');
    }

    public function destroy(Course $course): RedirectResponse
    {
        $this->courseService->deleteCourse($course);

        return redirect()->route('course-module.courses.index')->with('success', 'Course deleted successfully.');
    }
}
