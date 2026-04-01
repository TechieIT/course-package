<?php

namespace Techie\CourseModule\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Techie\CourseModule\Http\Requests\StoreFormRequest;
use Techie\CourseModule\Models\Course;
use Techie\CourseModule\Models\Form;
use Techie\CourseModule\Services\FormService;

class FormController extends Controller
{
    public function __construct(private readonly FormService $formService)
    {
    }

    public function index(): View
    {
        $forms = Form::query()->with(['course', 'attributes'])->latest()->paginate(15);

        return view('course-module::forms.index', compact('forms'));
    }

    public function create(): View
    {
        $courses = Course::query()->orderBy('title')->get();

        return view('course-module::forms.create', [
            'courses' => $courses,
            'form' => new Form(),
        ]);
    }

    public function store(StoreFormRequest $request): RedirectResponse
    {
        $this->formService->createForm($request->validated());

        return redirect()->route('course-module.forms.index')->with('success', 'Form created successfully.');
    }

    public function show(Form $form): View
    {
        $form->load(['course', 'attributes']);

        return view('course-module::forms.show', compact('form'));
    }

    public function edit(Form $form): View
    {
        $courses = Course::query()->orderBy('title')->get();

        return view('course-module::forms.edit', compact('form', 'courses'));
    }

    public function update(StoreFormRequest $request, Form $form): RedirectResponse
    {
        $payload = $request->validated();
        $payload['slug'] = $payload['slug'] ?? Str::slug($payload['name']);
        $payload['is_active'] = (bool) ($payload['is_active'] ?? false);

        $form->update($payload);

        return redirect()->route('course-module.forms.index')->with('success', 'Form updated successfully.');
    }

    public function destroy(Form $form): RedirectResponse
    {
        $form->delete();

        return redirect()->route('course-module.forms.index')->with('success', 'Form deleted successfully.');
    }
}
