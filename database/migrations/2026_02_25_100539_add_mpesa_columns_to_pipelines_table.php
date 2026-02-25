<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pipelines', function (Blueprint $table) {
            if (!Schema::hasColumn('pipelines', 'merchant_request_id')) {
                $table->string('merchant_request_id')->nullable()->after('paid_amount');
            }
            if (!Schema::hasColumn('pipelines', 'checkout_request_id')) {
                $table->string('checkout_request_id')->nullable()->after('merchant_request_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pipelines', function (Blueprint $table) {
            if (Schema::hasColumn('pipelines', 'merchant_request_id')) {
                $table->dropColumn('merchant_request_id');
            }
            if (Schema::hasColumn('pipelines', 'checkout_request_id')) {
                $table->dropColumn('checkout_request_id');
            }
        });
    }
};
