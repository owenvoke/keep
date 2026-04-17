<?php

declare(strict_types=1);

use App\Models\Keep;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('keeps', function (Blueprint $table) {
            $table->char('country', length: 2)->nullable()->after('region');
        });

        Keep::withoutTimestamps(function () {
            Keep::whereLike('region', 'GB-%')
                ->whereNull('country')
                ->update(['country' => 'GB']);
        });

        Schema::table('keeps', function (Blueprint $table) {
            $table->char('country', length: 2)->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('keeps', function (Blueprint $table) {
            $table->dropColumn('country');
        });
    }
};
