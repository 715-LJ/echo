<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('unit', 100)->nullable(false)->default('')->comment('姓');
            $table->string('phone', 255)->nullable(false)->default('')->comment('联系方式');
            $table->string('type', 255)->nullable(false)->default('')->comment('注册类型');
            $table->text('address')->nullable(false)->comment('地址');

            $table->timestamp('created_at')->nullable(false)->useCurrent()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate()->comment('更新时间');
            $table->timestamp('deleted_at')->nullable()->comment('删除时间');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('unit');
            $table->dropColumn('phone');
            $table->dropColumn('type');
            $table->dropColumn('address');
        });
    }
};
