<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plan_subscription_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plan_subscription_id');
            $table->unsignedBigInteger('plan_item_id');
            $table->string('item_slug');
            $table->string('item_name');
            $table->text('item_description')->nullable();
            $table->integer('value');
            $table->integer('used');
            $table->dateTime('valid_until')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['plan_subscription_id', 'plan_item_id']);
            $table->foreign('plan_subscription_id')->references('id')->on('plan_subscriptions')->onDelete('cascade');
            $table->foreign('plan_item_id')->references('id')->on('plan_items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plan_subscription_items');
    }
};
