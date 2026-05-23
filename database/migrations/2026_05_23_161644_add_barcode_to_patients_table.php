<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            // Barcode value (same as patient_id e.g. SCC-0001)
            $table->string('barcode_value')->nullable()->after('patient_id');
            // SVG barcode image stored as text
            $table->mediumText('barcode_svg')->nullable()->after('barcode_value');
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn(['barcode_value', 'barcode_svg']);
        });
    }
};
