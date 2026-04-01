@extends(config('course-module.use_cms_layout', true) ? config('course-module.layout', 'layouts.app') : 'course-module::layouts.app')

@section('content')
    <h1>Create Form</h1>
    <form method="POST" action="{{ route('course-module.forms.store') }}">
        @csrf
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" value="{{ old('name') }}" required>
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description">{{ old('description') }}</textarea>
        </div>
        <div class="mb-3">
            <label>Course</label>
            <select name="course_id">
                <option value="">-- Select Course --</option>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}" @selected((int) old('course_id') === $course->id)>{{ $course->title }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', true))>
                Active
            </label>
        </div>
        <button class="btn" type="submit">Save</button>
        <a class="btn btn-secondary" href="{{ route('course-module.forms.index') }}">Cancel</a>
    </form>
@endsection
