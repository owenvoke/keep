<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('collection_keep', function (Blueprint $table) {
            $table->foreignUuid('collection_uuid')->references('uuid')->on('collections')->onDelete('cascade');
            $table->foreignUuid('keep_uuid')->references('uuid')->on('keeps')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['collection_uuid', 'keep_uuid']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('collection_keep');
    }
};
