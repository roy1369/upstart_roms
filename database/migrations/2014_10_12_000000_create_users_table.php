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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 32)->comment('氏名');
            $table->string('name_kana', 32)->nullable(true)->comment('ふりがな');
            $table->string('email', 128)->unique()->comment('メールアドレス');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 128)->comment('パスワード');
            $table->rememberToken();
            $table->date('joining_date')->nullable(true)->comment('入社日');
            $table->date('ritirement_date')->nullable(true)->comment('退職日');
            $table->boolean('authority')->default(0)->comment('権限種別');
            $table->boolean('transportation_expenses_flag')->default(0)->comment('交通費フラグ');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
