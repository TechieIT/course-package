@extends(config('course-module.use_cms_layout', true) ? config('course-module.layout', 'layouts.app') : 'course-module::layouts.app')

@section('content')
    <h1>{{ $formAttribute->label }}</h1>
    <p><strong>Form:</strong> {{ $formAttribute->form?->name ?? '-' }}</p>
    <p><strong>Name:</strong> {{ $formAttribute->name }}</p>
    <p><strong>Type:</strong> {{ $formAttribute->type?->label() ?? $formAttribute->type }}</p>
    <p><strong>Required:</strong> {{ $formAttribute->is_required ? 'Yes' : 'No' }}</p>
    <p><strong>Placeholder:</strong> {{ $formAttribute->placeholder ?: '-' }}</p>
    <p><strong>Default:</strong> {{ $formAttribute->default_value ?: '-' }}</p>
    <p><strong>Validation:</strong> {{ $formAttribute->validation_rules ?: '-' }}</p>
    <p><strong>Options:</strong> {{ collect($formAttribute->options ?? [])->join(', ') ?: '-' }}</p>
    <p><strong>Order:</strong> {{ $formAttribute->order }}</p>
    <p>
        <a class="btn" href="{{ route('course-module.form-attributes.edit', $formAttribute) }}">Edit</a>
        <a class="btn btn-secondary" href="{{ route('course-module.form-attributes.index', ['form_id' => $formAttribute->form_id]) }}">Back</a>
    </p>
@endsection
