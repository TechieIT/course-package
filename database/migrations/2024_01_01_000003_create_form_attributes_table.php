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
        Schema::create($this->table('form_attributes'), function (Blueprint $table): void {
            $table->id();
            $table->foreignId('form_id')->constrained($this->table('forms'))->cascadeOnDelete();
            $table->string('label');
            $table->string('name');
            $table->enum('type', ['text', 'textarea', 'select', 'checkbox', 'radio', 'file', 'date', 'number', 'email'])->default('text');
            $table->string('placeholder')->nullable();
            $table->string('default_value')->nullable();
            $table->json('options')->nullable();
            $table->string('validation_rules')->nullable();
            $table->boolean('is_required')->default(false);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->table('form_attributes'));
    }
};
