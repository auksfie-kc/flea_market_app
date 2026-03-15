<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSoldItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sold_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('item_id')   //外部キー制約
                ->constrained()
                ->cascadeOnDelete()
                ->unique();

            $table->foreignId('user_id')    //外部キー制約
                ->constrained()
                ->cascadeOnDelete();

            $table->string('sold_postcode',7);
            $table->string('sold_address',255);
            $table->string('sold_building',255)->nullable();

            $table->string('payment_method', 50);  //credit_cardまたはconvenience_store
            $table->enum('status', ['paid', 'pending', 'canceled', 'failed'])
                ->default('pending');
            $table->string('stripe_checkout_session_id', 255)->nullable();

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
        Schema::dropIfExists('sold_items');
    }
}
