<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('m_property_unit', function (Blueprint $table) {
            $table->unsignedBigInteger('display_order')->nullable()->after('status_id');
        });
    }

    public function down(): void
    {
        Schema::table('m_property_unit', function (Blueprint $table) {
            $table->dropColumn('display_order');
        });
    }
};
