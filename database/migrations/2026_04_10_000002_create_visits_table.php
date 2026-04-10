<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->uuid()->unique()->primary();
            $table->foreignUuid('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignUuid('keep_uuid')->references('uuid')->on('keeps')->onDelete('cascade');
            $table->text('comment');
            $table->date('visited_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
