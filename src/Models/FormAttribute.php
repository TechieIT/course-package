<?php

namespace Techie\CourseModule\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Techie\CourseModule\Enums\FormAttributeType;

class FormAttribute extends Model
{
    protected $fillable = [
        'form_id',
        'label',
        'name',
        'type',
        'placeholder',
        'default_value',
        'options',
        'validation_rules',
        'is_required',
        'order',
    ];

    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
        'type' => FormAttributeType::class,
    ];

    public function getTable(): string
    {
        return config('course-module.table_prefix', '') . 'form_attributes';
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order');
    }
}
