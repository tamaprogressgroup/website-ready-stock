<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private array $tables = [
        'm_property_unit_trans',
        'm_property_unit',
        'm_property_facilities',
        'm_property_facilities_trans',
        'm_property_unit_interior_trans',
        'm_property_specs_trans',
        'm_property_unit_exstra_fitur_trans',
        'm_property_nearby_locations_trans',
        'm_property_condition_trans',
        'm_property_type_trans',
        'm_tags',
    ];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            try {
                DB::statement("ALTER TABLE `{$table}` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            } catch (\Exception $e) {
                // table might not exist, skip
            }
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $table) {
            try {
                DB::statement("ALTER TABLE `{$table}` CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci");
            } catch (\Exception $e) {
                //
            }
        }
    }
};
