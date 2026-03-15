<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                    ->unique()           // 1対1の関係
                    ->constrained()      // 外部キー制約
                    ->onDelete('cascade');   // ユーザー削除時にプロフィールも削除

            $table->string('img_url', 2048)->nullable();
            $table->string('postcode', 7)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('building', 255)->nullable();
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
        Schema::dropIfExists('profiles');
    }
}
