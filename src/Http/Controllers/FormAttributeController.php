<?php

namespace Techie\CourseModule\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Techie\CourseModule\Enums\FormAttributeType;
use Techie\CourseModule\Http\Requests\StoreFormAttributeRequest;
use Techie\CourseModule\Models\Form;
use Techie\CourseModule\Models\FormAttribute;

class FormAttributeController extends Controller
{
    public function index(Request $request): View
    {
        $forms = Form::query()->orderBy('name')->get();
        $formId = (int) ($request->integer('form_id') ?: ($forms->first()?->id ?? 0));
        $form = Form::query()->find($formId);
        $attributes = $form ? $form->attributes()->ordered()->get() : collect();

        return view('course-module::form-attributes.index', [
            'forms' => $forms,
            'form' => $form,
            'attributes' => $attributes,
            'types' => FormAttributeType::cases(),
        ]);
    }

    public function create(Request $request): View
    {
        $forms = Form::query()->orderBy('name')->get();

        return view('course-module::form-attributes.create', [
            'forms' => $forms,
            'formAttribute' => new FormAttribute(),
            'selectedFormId' => $request->integer('form_id'),
            'types' => FormAttributeType::cases(),
        ]);
    }

    public function store(StoreFormAttributeRequest $request): RedirectResponse
    {
        $payload = $request->validated();
        $payload['name'] = $payload['name'] ?? Str::slug($payload['label'], '_');
        $payload['is_required'] = (bool) ($payload['is_required'] ?? false);
        $payload['order'] = (int) ($payload['order'] ?? 0);

        FormAttribute::query()->create($payload);

        return redirect()
            ->route('course-module.form-attributes.index', ['form_id' => $payload['form_id']])
            ->with('success', 'Attribute created successfully.');
    }

    public function show(FormAttribute $formAttribute): View
    {
        $formAttribute->load('form');

        return view('course-module::form-attributes.show', [
            'formAttribute' => $formAttribute,
        ]);
    }

    public function edit(FormAttribute $formAttribute): View
    {
        $forms = Form::query()->orderBy('name')->get();

        return view('course-module::form-attributes.edit', [
            'forms' => $forms,
            'formAttribute' => $formAttribute,
            'types' => FormAttributeType::cases(),
        ]);
    }

    public function update(StoreFormAttributeRequest $request, FormAttribute $formAttribute): RedirectResponse
    {
        $payload = $request->validated();
        $payload['name'] = $payload['name'] ?? Str::slug($payload['label'], '_');
        $payload['is_required'] = (bool) ($payload['is_required'] ?? false);
        $payload['order'] = (int) ($payload['order'] ?? 0);
        $formAttribute->update($payload);

        return redirect()
            ->route('course-module.form-attributes.index', ['form_id' => $payload['form_id']])
            ->with('success', 'Attribute updated successfully.');
    }

    public function destroy(FormAttribute $formAttribute): RedirectResponse
    {
        $formId = $formAttribute->form_id;
        $formAttribute->delete();

        return redirect()
            ->route('course-module.form-attributes.index', ['form_id' => $formId])
            ->with('success', 'Attribute deleted successfully.');
    }

    public function reorder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'items' => ['required', 'array'],
            'items.*.id' => ['required', 'integer'],
            'items.*.order' => ['required', 'integer'],
        ]);

        foreach ($validated['items'] as $item) {
            FormAttribute::query()
                ->where('id', $item['id'])
                ->update(['order' => $item['order']]);
        }

        return response()->json(['message' => 'Reordered successfully.']);
    }
}
