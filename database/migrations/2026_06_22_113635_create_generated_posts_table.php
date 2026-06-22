<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('generated_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('raw_content_id')->constrained()->cascadeOnDelete();
            $table->foreignId('blueprint_id')->constrained()->cascadeOnDelete();
            $table->string('hook_propose', 280);
            $table->json('body_points');
            $table->integer('technical_readability_score');
            $table->json('suggested_hashtags');
            $table->text('tone_compliance_justification');
            $table->longText('generated_text')->nullable();
            $table->string('status')->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('generated_posts');
    }
};
