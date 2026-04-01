@extends(config('course-module.use_cms_layout', true) ? config('course-module.layout', 'layouts.app') : 'course-module::layouts.app')

@section('content')
    <h1>Forms</h1>
    <p>
        <a class="btn" href="{{ route('course-module.forms.create') }}">Create Form</a>
        <a class="btn btn-secondary" href="{{ route('course-module.courses.index') }}">Back to Courses</a>
    </p>

    <table>
        <thead>
        <tr>
            <th>Name</th>
            <th>Course</th>
            <th>Attributes</th>
            <th>Active</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse($forms as $form)
            <tr>
                <td>{{ $form->name }}</td>
                <td>{{ $form->course?->title ?? '-' }}</td>
                <td>{{ $form->attributes->count() }}</td>
                <td>{{ $form->is_active ? 'Yes' : 'No' }}</td>
                <td>
                    <a class="btn btn-secondary" href="{{ route('course-module.forms.show', $form) }}">Show</a>
                    <a class="btn" href="{{ route('course-module.forms.edit', $form) }}">Edit</a>
                    <a class="btn btn-secondary" href="{{ route('course-module.form-attributes.index', ['form_id' => $form->id]) }}">Attributes</a>
                    <form method="POST" action="{{ route('course-module.forms.destroy', $form) }}" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5">No forms found.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    {{ $forms->links() }}
@endsection
