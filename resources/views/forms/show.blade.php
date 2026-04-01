@extends(config('course-module.use_cms_layout', true) ? config('course-module.layout', 'layouts.app') : 'course-module::layouts.app')

@section('content')
    <h1>{{ $form->name }}</h1>
    <p><strong>Slug:</strong> {{ $form->slug }}</p>
    <p><strong>Course:</strong> {{ $form->course?->title ?? '-' }}</p>
    <p><strong>Active:</strong> {{ $form->is_active ? 'Yes' : 'No' }}</p>
    <p><strong>Description:</strong> {{ $form->description ?: '-' }}</p>
    <p>
        <a class="btn btn-secondary" href="{{ route('course-module.forms.index') }}">Back</a>
        <a class="btn" href="{{ route('course-module.form-attributes.index', ['form_id' => $form->id]) }}">Manage Attributes</a>
    </p>

    <h3>Rendered HTML Preview</h3>
    <div style="padding: 1rem; border: 1px solid #ddd;">
        {!! app(\Techie\CourseModule\Services\FormService::class)->renderFormHtml($form) !!}
    </div>
@endsection
