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
        Schema::table('header_footer', function (Blueprint $table) {
            $table->boolean('gst_status')->default(false)->after('mobile_no');
            $table->decimal('gst_percentage', 8, 2)->default(0)->after('gst_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('header_footer', function (Blueprint $table) {
            $table->dropColumn(['gst_status', 'gst_percentage']);
        });
    }
};
