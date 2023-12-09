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
        Schema::create('batch_logs', function (Blueprint $table) {
            $table->id();
            $table->string('batch_name', 64)->comment('バッチ名');
            $table->string('ending_kubun', 16)->nullable(true)->comment('終了区分');
            $table->datetime('start_date_and_time')->comment('開始日時');
            $table->datetime('ending_date_and_time')->nullable(true)->comment('終了日時');
            $table->text('message')->nullable(true)->comment('メッセージ');
            $table->text('error_stack_trace')->nullable(true)->comment('エラースタックトレース');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batch_logs');
    }
};