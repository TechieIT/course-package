<?php

namespace Techie\CourseModule\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Techie\CourseModule\Enums\FormAttributeType;
use Techie\CourseModule\Models\Form;

class StoreFormAttributeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'form_id' => ['required', Rule::exists((new Form())->getTable(), 'id')],
            'label' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(array_map(fn (FormAttributeType $type): string => $type->value, FormAttributeType::cases()))],
            'placeholder' => ['nullable', 'string', 'max:255'],
            'default_value' => ['nullable', 'string', 'max:255'],
            'options' => ['nullable', 'array'],
            'validation_rules' => ['nullable', 'string', 'max:255'],
            'is_required' => ['nullable', 'boolean'],
            'order' => ['nullable', 'integer'],
        ];
    }
}
