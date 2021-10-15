<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebsiteSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('website_settings', function (Blueprint $table) {
            $table->id();
            $table->string('website_title')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('phone_no')->nullable();
            $table->string('address')->nullable();
            $table->string('paypal_email')->nullable();
            $table->string('stripe_api_pk')->nullable();
            $table->string('stripe_api_sk')->nullable();
            $table->boolean('facebook_check')->nullable();
            $table->string('facebook')->nullable();
            $table->boolean('twitter_check')->nullable();
            $table->string('twitter')->nullable();
            $table->boolean('linkedin_check')->nullable();
            $table->string('linkedin')->nullable();
            $table->boolean('instagram_check')->nullable();
            $table->string('instagram')->nullable();
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
        Schema::dropIfExists('website_settings');
    }
}
