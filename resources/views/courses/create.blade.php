@extends(config('course-module.use_cms_layout', true) ? config('course-module.layout', 'layouts.app') : 'course-module::layouts.app')

@section('content')
    <h1>Create Course</h1>
    <form method="POST" action="{{ route('course-module.courses.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label>Title</label>
            <input type="text" name="title" value="{{ old('title') }}" required>
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description">{{ old('description') }}</textarea>
        </div>
        <div class="row">
            <div class="mb-3">
                <label>Status</label>
                <select name="status" required>
                    @foreach($statuses as $status)
                        <option value="{{ $status->value }}" @selected(old('status') === $status->value)>{{ $status->hasLabel() }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label>Price</label>
                <input type="number" step="0.01" min="0" name="price" value="{{ old('price') }}">
            </div>
        </div>
        <div class="row">
            <div class="mb-3">
                <label>Form</label>
                <select name="form_id">
                    <option value="">-- Select Form --</option>
                    @foreach($forms as $form)
                        <option value="{{ $form->id }}" @selected((int) old('form_id') === $form->id)>{{ $form->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label>Order</label>
                <input type="number" name="order" value="{{ old('order', 0) }}">
            </div>
        </div>
        <div class="mb-3">
            <label>
                <input type="checkbox" name="is_free" value="1" @checked(old('is_free'))>
                Is Free
            </label>
        </div>
        <div class="mb-3">
            <label>Thumbnail</label>
            <input type="file" name="thumbnail" accept="image/*">
        </div>
        <button class="btn" type="submit">Save</button>
        <a class="btn btn-secondary" href="{{ route('course-module.courses.index') }}">Cancel</a>
    </form>
@endsection
