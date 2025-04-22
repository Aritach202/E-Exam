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
        Schema::create('leave_request', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('department')->nullable();
            $table->string('email')->nullable();
            $table->string('phone');
            $table->enum('leave_type',['ลาป่วย','ลากิจ','พักร้อน','อื่นๆ'])->default('อื่นๆ');
            $table->text('reason');
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamp('submitted_at')->useCurrent();
            $table->enum('status',['รอพิจารณา','อนุมัติ','ไม่อนุมัติ'])->default('รอพิจารณา');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_request');
    }
};
