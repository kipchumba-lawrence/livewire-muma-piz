<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
 public function up()
    {
        Schema::create('pipelines', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->timestamp('booked_time');
            $table->string('package');
            $table->string('venue');
            $table->text('note')->nullable();
            $table->string('phone');
            $table->string('editor')->nullable();
            $table->string('editing_status')->default('pending');
            $table->string('shoot_status')->default('pending');
            $table->text('image_collection')->nullable();
            $table->string('export_link')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pipelines');
    }
};
