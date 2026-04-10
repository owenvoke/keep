<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keeps', function (Blueprint $table) {
            $table->uuid()->unique()->primary();
            $table->string('name');
            $table->string('region');
            $table->json('coordinates');
            $table->string('built');
            $table->string('condition');
            $table->string('owned_by');
            $table->string('type');
            $table->boolean('accessible')->default(true);
            $table->json('alternative_names')->nullable();
            $table->string('description')->nullable();
            $table->string('homepage')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keeps');
    }
};
