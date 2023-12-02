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
        Schema::create('various_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->comment('ユーザーID');
            $table->integer('type', 11)->comment('申請種別');
            $table->date('result')->comment('申請期間');
            $table->integer('status', 11)->comment('申請状況');
            $table->time('correction_start_time')->nullable(true)->comment('修正出勤時間');
            $table->time('correction_end_time')->nullable(true)->comment('修正退勤時間');
            $table->text('comment')->comment('申請理由');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('various_requests');
    }
};
