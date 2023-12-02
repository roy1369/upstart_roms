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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->comment('ユーザーID');
            $table->date('date')->comment('年月日');
            $table->string('start_address', 255)->comment('出勤住所');
            $table->time('start_time')->comment('出勤時間');
            $table->integer('working_address')->comment('勤務先');
            $table->integer('working_type')->comment('勤務形態');
            $table->string('end_address', 255)->nullable(true)->comment('退勤住所');
            $table->time('end_time')->nullable(true)->comment('退勤時間');
            $table->time('working_time')->nullable(true)->comment('勤務時間');
            $table->time('rest_time')->nullable(true)->comment('休憩時間');
            $table->time('over_time')->nullable(true)->comment('残業時間');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
