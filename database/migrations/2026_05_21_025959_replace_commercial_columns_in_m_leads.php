<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE m_leads CHANGE commercial_id cluster_id INT(10) UNSIGNED NULL');
        DB::statement('ALTER TABLE m_leads CHANGE commercial_unit_type_id property_id INT(10) UNSIGNED NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE m_leads CHANGE cluster_id commercial_id INT(10) UNSIGNED NULL');
        DB::statement('ALTER TABLE m_leads CHANGE property_id commercial_unit_type_id INT(10) UNSIGNED NULL');
    }
};
