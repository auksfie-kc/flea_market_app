<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('likes', function (Blueprint $table) {

            $table->id();

            // 出品者/外部キー
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // 商品ID/外部キー
            $table->foreignId('item_id')
                ->constrained()
                ->cascadeOnDelete();

            // 重複防止
            $table->unique(['user_id', 'item_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('likes');
    }
}
