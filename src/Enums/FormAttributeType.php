<?php

namespace Techie\CourseModule\Enums;

enum FormAttributeType: string
{
    case Text = 'text';
    case Textarea = 'textarea';
    case Select = 'select';
    case Checkbox = 'checkbox';
    case Radio = 'radio';
    case File = 'file';
    case Date = 'date';
    case Number = 'number';
    case Email = 'email';

    public function label(): string
    {
        return match ($this) {
            self::Text => 'Text',
            self::Textarea => 'Textarea',
            self::Select => 'Select',
            self::Checkbox => 'Checkbox',
            self::Radio => 'Radio',
            self::File => 'File',
            self::Date => 'Date',
            self::Number => 'Number',
            self::Email => 'Email',
        };
    }
}
