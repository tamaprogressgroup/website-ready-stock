<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_seo', function (Blueprint $table) {
            $table->id();
            $table->string('page_key', 50)->unique();
            $table->string('meta_title', 255)->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keyword', 500)->nullable();
            $table->string('og_title', 255)->nullable();
            $table->text('og_description')->nullable();
            $table->timestamps();
        });

        DB::table('page_seo')->insert([
            ['page_key' => 'home',         'meta_title' => 'Paradise Ready Stock | Properti Siap Huni Terbaik', 'created_at' => now(), 'updated_at' => now()],
            ['page_key' => 'all_products', 'meta_title' => 'Semua Properti | Paradise Ready Stock',             'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('page_seo');
    }
};
