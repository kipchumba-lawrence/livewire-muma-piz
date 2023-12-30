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
        Schema::table('pipelines', function (Blueprint $table) {
            // Add payment_status column with default value 'pending'
            $table->string('payment_status')->default('pending')->after('export_link');

            // Add editstart column after shoot_status
            $table->timestamp('editstart')->nullable()->after('shoot_status');

            // Add total_amount column after payment_status
            $table->decimal('total_amount')->after('payment_status');
            $table->decimal('paid_amount')->after('total_amount');
            $table->integer('numberofpix')->after('editstart');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pipelines', function (Blueprint $table) {
            //
        });
    }
};
