<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('m_leads', function (Blueprint $table) {
            $table->dropColumn(['property_id', 'name', 'phone', 'source', 'status', 'craeted_at']);
        });

        Schema::table('m_leads', function (Blueprint $table) {
            $table->string('email', 50)->nullable()->change();

            $table->string('salutation', 50)->nullable()->after('id');
            $table->string('fullname', 70)->nullable()->after('salutation');
            $table->string('phone_number', 15)->nullable()->after('email');
            $table->string('enquiry', 50)->nullable()->after('phone_number');
            $table->unsignedInteger('township_id')->nullable()->after('enquiry');
            $table->unsignedInteger('commercial_id')->nullable()->after('township_id');
            $table->unsignedInteger('commercial_unit_type_id')->nullable()->after('commercial_id');
            $table->dateTime('created_at')->nullable()->after('commercial_unit_type_id');
            $table->text('params')->nullable()->after('created_at');
            $table->string('hutk', 250)->nullable()->after('params');
            $table->string('sumber_informasi', 255)->nullable()->after('hutk');
            $table->string('datang_dengan_siapa', 255)->nullable()->after('sumber_informasi');
            $table->boolean('kirim_brosur')->nullable()->after('datang_dengan_siapa');
            $table->string('berminat_cari', 255)->nullable()->after('kirim_brosur');
            $table->string('rencana_beli', 255)->nullable()->after('berminat_cari');
            $table->string('jumlah_kamar', 255)->nullable()->after('rencana_beli');
            $table->boolean('hubspot_submit')->nullable()->after('jumlah_kamar');
            $table->string('remote_addr', 255)->nullable()->after('hubspot_submit');
            $table->string('contact_form_id', 100)->nullable()->after('remote_addr');
            $table->text('url_form')->nullable()->after('contact_form_id');
            $table->text('url_origin')->nullable()->after('url_form');
        });
    }

    public function down(): void
    {
        Schema::table('m_leads', function (Blueprint $table) {
            $table->dropColumn([
                'salutation', 'fullname', 'phone_number', 'enquiry',
                'township_id', 'commercial_id', 'commercial_unit_type_id',
                'created_at', 'params', 'hutk', 'sumber_informasi',
                'datang_dengan_siapa', 'kirim_brosur', 'berminat_cari',
                'rencana_beli', 'jumlah_kamar', 'hubspot_submit',
                'remote_addr', 'contact_form_id', 'url_form', 'url_origin',
            ]);
        });

        Schema::table('m_leads', function (Blueprint $table) {
            $table->string('email', 255)->nullable()->change();
            $table->integer('property_id')->nullable();
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('source')->nullable();
            $table->string('status')->nullable();
            $table->dateTime('craeted_at')->nullable();
        });
    }
};
