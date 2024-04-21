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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->json('name');
            $table->json('description')->nullable();
            $table->integer('price')->default('0');
            $table->integer('trial_duration')->default(0);
            $table->string('trial_duration_type')->default('day');
            $table->integer('package_duration')->default(0);
            $table->string('package_duration_type')->default('month');
            $table->integer('subscriptions_limit')->nullable();
            $table->date('issue_date')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['slug']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plans');
    }
};
