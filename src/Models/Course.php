<?php

namespace Techie\CourseModule\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Techie\CourseModule\Enums\CourseStatus;
use Techie\CourseModule\Models\Form;
use Techie\CourseModule\Models\FormAttribute;

class Course extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'thumbnail',
        'status',
        'price',
        'is_free',
        'order',
        'form_id',
        'created_by',
    ];

    protected $casts = [
        'is_free' => 'boolean',
        'price' => 'decimal:2',
        'status' => CourseStatus::class,
    ];

    public function getTable(): string
    {
        return config('course-module.table_prefix', '') . 'courses';
    }

    protected static function booted(): void
    {
        static::creating(function (self $course): void {
            if (empty($course->slug) && ! empty($course->title)) {
                $course->slug = Str::slug($course->title);
            }
        });

        static::updating(function (self $course): void {
            if (empty($course->slug) && ! empty($course->title)) {
                $course->slug = Str::slug($course->title);
            }
        });
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'created_by');
    }

    public function formAttributes(): HasManyThrough
    {
        return $this->hasManyThrough(
            FormAttribute::class,
            Form::class,
            'id',
            'form_id',
            'form_id',
            'id'
        );
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', CourseStatus::Published->value);
    }

    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', CourseStatus::Draft->value);
    }
}
