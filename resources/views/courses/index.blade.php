@extends(config('course-module.use_cms_layout', true) ? config('course-module.layout', 'layouts.app') : 'course-module::layouts.app')

@section('content')
    <h1>Courses</h1>
    <p>
        <a class="btn" href="{{ route('course-module.courses.create') }}">Create Course</a>
        <a class="btn btn-secondary" href="{{ route('course-module.forms.index') }}">Manage Forms</a>
    </p>

    <table>
        <thead>
        <tr>
            <th>Title</th>
            <th>Status</th>
            <th>Price</th>
            <th>Form</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse($courses as $course)
            <tr>
                <td>{{ $course->title }}</td>
                <td>
                    @php $status = $course->status; @endphp
                    <span class="badge badge-{{ $status?->color() ?? 'secondary' }}">{{ $status?->hasLabel() ?? 'Unknown' }}</span>
                </td>
                <td>{{ $course->is_free ? 'Free' : number_format((float) $course->price, 2) }}</td>
                <td>{{ $course->form?->name ?? '-' }}</td>
                <td>
                    <a class="btn btn-secondary" href="{{ route('course-module.courses.show', $course) }}">Show</a>
                    <a class="btn" href="{{ route('course-module.courses.edit', $course) }}">Edit</a>
                    <form method="POST" action="{{ route('course-module.courses.destroy', $course) }}" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5">No courses found.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    {{ $courses->links() }}
@endsection
