<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('short_links', function (Blueprint $table) {
            $table->id();
            $table->string('code', 12)->unique();
            $table->text('key_hash');
            $table->string('redirect_path', 500)->nullable(); // e.g. /properti-baru/rumah/.../slug
            $table->unsignedInteger('hits')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('short_links'); }
};
