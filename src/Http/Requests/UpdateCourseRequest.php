<?php

namespace Techie\CourseModule\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Techie\CourseModule\Enums\CourseStatus;
use Techie\CourseModule\Models\Form;

class UpdateCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::in(array_map(fn (CourseStatus $status): string => $status->value, CourseStatus::cases()))],
            'price' => ['nullable', 'numeric', 'min:0'],
            'is_free' => ['nullable', 'boolean'],
            'form_id' => ['nullable', Rule::exists((new Form())->getTable(), 'id')],
            'thumbnail' => ['nullable', 'image', 'max:2048'],
            'order' => ['nullable', 'integer'],
        ];
    }
}
