<?php

namespace Techie\CourseModule\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Techie\CourseModule\Models\Course;

class CourseService
{
    public function createCourse(array $data): Course
    {
        $data['slug'] = $data['slug'] ?? Str::slug((string) ($data['title'] ?? ''));

        if (($data['thumbnail'] ?? null) instanceof UploadedFile) {
            $data['thumbnail'] = $this->storeThumbnail($data['thumbnail']);
        }

        $data['is_free'] = (bool) ($data['is_free'] ?? false);
        $data['created_by'] = $data['created_by'] ?? auth()->id();

        return Course::query()->create($data);
    }

    public function updateCourse(Course $course, array $data): Course
    {
        if (($data['thumbnail'] ?? null) instanceof UploadedFile) {
            $this->deleteThumbnail((string) $course->thumbnail);
            $data['thumbnail'] = $this->storeThumbnail($data['thumbnail']);
        }

        if (empty($data['slug']) && ! empty($data['title'])) {
            $data['slug'] = Str::slug((string) $data['title']);
        }

        $data['is_free'] = isset($data['is_free']) ? (bool) $data['is_free'] : $course->is_free;

        $course->update($data);

        return $course->refresh();
    }

    public function deleteCourse(Course $course): void
    {
        $this->deleteThumbnail((string) $course->thumbnail);
        $course->delete();
    }

    private function storeThumbnail(UploadedFile $file): string
    {
        return $file->store(
            config('course-module.thumbnail_path', 'courses/thumbnails'),
            config('course-module.thumbnail_disk', 'public')
        );
    }

    private function deleteThumbnail(?string $path): void
    {
        if (empty($path)) {
            return;
        }

        Storage::disk(config('course-module.thumbnail_disk', 'public'))->delete($path);
    }
}
