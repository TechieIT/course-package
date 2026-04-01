<?php

namespace Techie\CourseModule\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Form extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
        'course_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getTable(): string
    {
        return config('course-module.table_prefix', '') . 'forms';
    }

    protected static function booted(): void
    {
        static::creating(function (self $form): void {
            if (empty($form->slug) && ! empty($form->name)) {
                $form->slug = Str::slug($form->name);
            }
        });
    }

    public function attributes(): HasMany
    {
        return $this->hasMany(FormAttribute::class)->orderBy('order');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
