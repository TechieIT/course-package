<?php

namespace Techie\CourseModule\Services;

use Illuminate\Support\Str;
use Techie\CourseModule\Enums\FormAttributeType;
use Techie\CourseModule\Models\Form;
use Techie\CourseModule\Models\FormAttribute;

class FormService
{
    public function createForm(array $data): Form
    {
        $data['slug'] = $data['slug'] ?? Str::slug((string) ($data['name'] ?? ''));
        $data['is_active'] = (bool) ($data['is_active'] ?? false);

        return Form::query()->create($data);
    }

    public function syncAttributes(Form $form, array $attributes): void
    {
        foreach (array_values($attributes) as $index => $attribute) {
            $payload = [
                'form_id' => $form->id,
                'label' => $attribute['label'],
                'name' => $attribute['name'] ?? Str::slug($attribute['label'], '_'),
                'type' => $attribute['type'] ?? FormAttributeType::Text->value,
                'placeholder' => $attribute['placeholder'] ?? null,
                'default_value' => $attribute['default_value'] ?? null,
                'options' => $attribute['options'] ?? null,
                'validation_rules' => $attribute['validation_rules'] ?? null,
                'is_required' => (bool) ($attribute['is_required'] ?? false),
                'order' => $attribute['order'] ?? $index,
            ];

            if (! empty($attribute['id'])) {
                FormAttribute::query()
                    ->where('id', $attribute['id'])
                    ->where('form_id', $form->id)
                    ->update($payload);
                continue;
            }

            $form->attributes()->create($payload);
        }
    }

    public function renderFormHtml(Form $form): string
    {
        $html = '';

        foreach ($form->attributes()->ordered()->get() as $attribute) {
            $name = e($attribute->name);
            $label = e($attribute->label);
            $placeholder = e((string) $attribute->placeholder);
            $required = $attribute->is_required ? ' required' : '';
            $defaultValue = e((string) $attribute->default_value);

            if ($attribute->type === FormAttributeType::Textarea) {
                $html .= "<label>{$label}</label><textarea name=\"{$name}\" placeholder=\"{$placeholder}\"{$required}>{$defaultValue}</textarea>";
                continue;
            }

            if (in_array($attribute->type, [FormAttributeType::Select, FormAttributeType::Radio, FormAttributeType::Checkbox], true)) {
                $options = (array) ($attribute->options ?? []);

                if ($attribute->type === FormAttributeType::Select) {
                    $html .= "<label>{$label}</label><select name=\"{$name}\"{$required}>";
                    foreach ($options as $option) {
                        $value = e((string) $option);
                        $html .= "<option value=\"{$value}\">{$value}</option>";
                    }
                    $html .= '</select>';
                    continue;
                }

                $html .= "<fieldset><legend>{$label}</legend>";
                foreach ($options as $option) {
                    $value = e((string) $option);
                    $fieldName = $attribute->type === FormAttributeType::Checkbox ? "{$name}[]" : $name;
                    $type = $attribute->type->value;
                    $html .= "<label><input type=\"{$type}\" name=\"{$fieldName}\" value=\"{$value}\"{$required}> {$value}</label>";
                }
                $html .= '</fieldset>';
                continue;
            }

            $type = e($attribute->type->value);
            $html .= "<label>{$label}</label><input type=\"{$type}\" name=\"{$name}\" value=\"{$defaultValue}\" placeholder=\"{$placeholder}\"{$required}>";
        }

        return $html;
    }
}
