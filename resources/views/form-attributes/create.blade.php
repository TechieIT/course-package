@extends(config('course-module.use_cms_layout', true) ? config('course-module.layout', 'layouts.app') : 'course-module::layouts.app')

@section('content')
    <h1>Create Form Attribute</h1>
    <form method="POST" action="{{ route('course-module.form-attributes.store') }}">
        @csrf
        <div class="mb-3">
            <label>Form</label>
            <select name="form_id" required>
                @foreach($forms as $form)
                    <option value="{{ $form->id }}" @selected((int) old('form_id', $selectedFormId) === $form->id)>{{ $form->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Label</label>
            <input type="text" name="label" value="{{ old('label') }}" required>
        </div>
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" value="{{ old('name') }}" required>
        </div>
        <div class="mb-3">
            <label>Type</label>
            <select name="type" required>
                @foreach($types as $type)
                    <option value="{{ $type->value }}" @selected(old('type') === $type->value)>{{ $type->label() }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Placeholder</label>
            <input type="text" name="placeholder" value="{{ old('placeholder') }}">
        </div>
        <div class="mb-3">
            <label>Default Value</label>
            <input type="text" name="default_value" value="{{ old('default_value') }}">
        </div>
        <div class="mb-3">
            <label>Options (one per line)</label>
            <textarea name="options_text">{{ old('options_text') }}</textarea>
        </div>
        <div class="mb-3">
            <label>Validation Rules</label>
            <input type="text" name="validation_rules" value="{{ old('validation_rules') }}">
        </div>
        <div class="mb-3">
            <label>
                <input type="checkbox" name="is_required" value="1" @checked(old('is_required'))>
                Required
            </label>
        </div>
        <div class="mb-3">
            <label>Order</label>
            <input type="number" name="order" value="{{ old('order', 0) }}">
        </div>
        <button class="btn" type="submit">Save</button>
        <a class="btn btn-secondary" href="{{ route('course-module.form-attributes.index', ['form_id' => old('form_id', $selectedFormId)]) }}">Cancel</a>
    </form>

    <script>
        const form = document.querySelector('form');
        form.addEventListener('submit', function () {
            const optionsText = form.querySelector('[name="options_text"]').value.trim();
            if (!optionsText) return;

            optionsText.split('\n').map(v => v.trim()).filter(Boolean).forEach((option, index) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `options[${index}]`;
                input.value = option;
                form.appendChild(input);
            });
        });
    </script>
@endsection
