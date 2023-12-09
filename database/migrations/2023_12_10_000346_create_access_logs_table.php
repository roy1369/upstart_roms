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
        Schema::create('access_logs', function (Blueprint $table) {
            $table->id();
            $table->text('access_url')->nullable(true)->comment('アクセスURL');
            $table->string('kubun')->nullable(true)->comment('POSTorGET');
            $table->text('form_value')->nullable(true)->comment('フォーム値');
            $table->text('user_agent')->nullable(true)->comment('ユーザーエージェント');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('access_logs');
    }
};