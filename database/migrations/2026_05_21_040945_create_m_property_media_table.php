<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('m_property_media')) return;

        Schema::create('m_property_media', function (Blueprint $table) {
            $table->increments('media_id');
            $table->unsignedInteger('property_id')->nullable();
            $table->string('filename')->nullable();
            $table->string('url_360')->nullable();
            $table->string('url_youtube')->nullable();
            $table->unsignedInteger('created_user_id')->nullable();
            $table->dateTime('created_datetime')->nullable();
            $table->unsignedInteger('updated_user_id')->nullable();
            $table->dateTime('updated_datetime')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_property_media');
    }
};
