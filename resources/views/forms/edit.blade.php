@extends(config('course-module.use_cms_layout', true) ? config('course-module.layout', 'layouts.app') : 'course-module::layouts.app')

@section('content')
    <h1>Edit Form</h1>
    <form method="POST" action="{{ route('course-module.forms.update', $form) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" value="{{ old('name', $form->name) }}" required>
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description">{{ old('description', $form->description) }}</textarea>
        </div>
        <div class="mb-3">
            <label>Course</label>
            <select name="course_id">
                <option value="">-- Select Course --</option>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}" @selected((int) old('course_id', $form->course_id) === $course->id)>{{ $course->title }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $form->is_active))>
                Active
            </label>
        </div>
        <button class="btn" type="submit">Update</button>
        <a class="btn btn-secondary" href="{{ route('course-module.forms.index') }}">Cancel</a>
    </form>
@endsection
