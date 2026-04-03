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
            if (!Schema::hasColumn('header_footer', 'whatsapp_no')) {
                $table->string('whatsapp_no')->nullable()->after('mobile_no');
            }
            if (!Schema::hasColumn('header_footer', 'whatsapp_msg_1')) {
                $table->string('whatsapp_msg_1')->nullable()->after('whatsapp_no');
            }
            if (!Schema::hasColumn('header_footer', 'whatsapp_msg_2')) {
                $table->string('whatsapp_msg_2')->nullable()->after('whatsapp_msg_1');
            }
            if (!Schema::hasColumn('header_footer', 'whatsapp_msg_3')) {
                $table->string('whatsapp_msg_3')->nullable()->after('whatsapp_msg_2');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('header_footer', function (Blueprint $table) {
            if (Schema::hasColumn('header_footer', 'whatsapp_no')) {
                $table->dropColumn('whatsapp_no');
            }
            if (Schema::hasColumn('header_footer', 'whatsapp_msg_1')) {
                $table->dropColumn('whatsapp_msg_1');
            }
            if (Schema::hasColumn('header_footer', 'whatsapp_msg_2')) {
                $table->dropColumn('whatsapp_msg_2');
            }
            if (Schema::hasColumn('header_footer', 'whatsapp_msg_3')) {
                $table->dropColumn('whatsapp_msg_3');
            }
        });
    }
};
