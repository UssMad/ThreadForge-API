<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blueprints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('audience_target');
            $table->string('tone');
            $table->integer('max_hashtags');
            $table->integer('max_characters');
            $table->text('additional_rules')->nullable();
            $table->timestamps();
        });
    }

    protected function casts(): array
    {
        return [
            'additional_rules' => 'array',
        ];
    }

    public function down(): void
    {
        Schema::dropIfExists('blueprints');
    }
};
