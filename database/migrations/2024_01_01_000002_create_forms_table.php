<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    private function table(string $name): string
    {
        return config('course-module.table_prefix', '') . $name;
    }

    public function up(): void
    {
        Schema::create($this->table('forms'), function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('course_id')->nullable()->constrained($this->table('courses'))->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table($this->table('courses'), function (Blueprint $table): void {
            $table->foreign('form_id')->references('id')->on($this->table('forms'))->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table($this->table('courses'), function (Blueprint $table): void {
            $table->dropForeign(['form_id']);
        });

        Schema::dropIfExists($this->table('forms'));
    }
};
