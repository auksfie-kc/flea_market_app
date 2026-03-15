<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();

            // 出品者/外部キー
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // 商品状態/外部キー
            $table->foreignId('condition_id')
                ->constrained()
                ->restrictOnDelete();

            // 商品情報
            $table->string('name', 255);
            $table->string('img_url', 2048)->nullable();
            $table->string('brand', 255)->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('price');

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
        Schema::dropIfExists('items');
    }
}
