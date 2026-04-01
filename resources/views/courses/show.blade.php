@extends(config('course-module.use_cms_layout', true) ? config('course-module.layout', 'layouts.app') : 'course-module::layouts.app')

@section('content')
    <h1>{{ $course->title }}</h1>
    <p><strong>Status:</strong> {{ $course->status?->hasLabel() }}</p>
    <p><strong>Slug:</strong> {{ $course->slug }}</p>
    <p><strong>Description:</strong> {{ $course->description ?: '-' }}</p>
    <p><strong>Price:</strong> {{ $course->is_free ? 'Free' : number_format((float) $course->price, 2) }}</p>
    <p><strong>Form:</strong> {{ $course->form?->name ?: '-' }}</p>
    <p>
        <a class="btn" href="{{ route('course-module.courses.edit', $course) }}">Edit</a>
        <a class="btn btn-secondary" href="{{ route('course-module.courses.index') }}">Back</a>
    </p>

    <h3>Linked Form Attributes</h3>
    <table>
        <thead>
        <tr>
            <th>Label</th>
            <th>Name</th>
            <th>Type</th>
            <th>Required</th>
        </tr>
        </thead>
        <tbody>
        @forelse($course->formAttributes as $attribute)
            <tr>
                <td>{{ $attribute->label }}</td>
                <td>{{ $attribute->name }}</td>
                <td>{{ $attribute->type?->label() ?? $attribute->type }}</td>
                <td>{{ $attribute->is_required ? 'Yes' : 'No' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4">No linked attributes.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
@endsection
