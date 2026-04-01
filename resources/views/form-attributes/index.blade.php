@extends(config('course-module.use_cms_layout', true) ? config('course-module.layout', 'layouts.app') : 'course-module::layouts.app')

@section('content')
    <h1>Form Attributes</h1>

    <form method="GET" action="{{ route('course-module.form-attributes.index') }}">
        <label>Select Form</label>
        <select name="form_id" onchange="this.form.submit()">
            @foreach($forms as $item)
                <option value="{{ $item->id }}" @selected($form && $form->id === $item->id)>{{ $item->name }}</option>
            @endforeach
        </select>
    </form>

    @if($form)
        <p style="margin-top: 1rem;">
            <a class="btn" href="{{ route('course-module.form-attributes.create', ['form_id' => $form->id]) }}">Add Attribute</a>
            <a class="btn btn-secondary" href="{{ route('course-module.forms.index') }}">Back to Forms</a>
        </p>

        <table id="sortable-attributes">
            <thead>
            <tr>
                <th>Label</th>
                <th>Type</th>
                <th>Required</th>
                <th>Order</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse($attributes as $attribute)
                <tr data-id="{{ $attribute->id }}">
                    <td>{{ $attribute->label }}</td>
                    <td><span class="badge badge-primary">{{ $attribute->type?->label() ?? $attribute->type }}</span></td>
                    <td>{{ $attribute->is_required ? 'Yes' : 'No' }}</td>
                    <td>{{ $attribute->order }}</td>
                    <td>
                        <a class="btn btn-secondary" href="{{ route('course-module.form-attributes.show', $attribute) }}">Show</a>
                        <a class="btn" href="{{ route('course-module.form-attributes.edit', $attribute) }}">Edit</a>
                        <form method="POST" action="{{ route('course-module.form-attributes.destroy', $attribute) }}" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No attributes found for this form.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    @else
        <p>No forms available. Create a form first.</p>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
    <script>
        const tbody = document.querySelector('#sortable-attributes tbody');
        if (tbody) {
            new Sortable(tbody, {
                animation: 150,
                onEnd: function () {
                    const items = Array.from(tbody.querySelectorAll('tr')).map((row, index) => ({
                        id: Number(row.dataset.id),
                        order: index
                    }));

                    fetch('{{ route('course-module.form-attributes.reorder') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({items})
                    });
                }
            });
        }
    </script>
@endsection
