<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('thumbnail');
            $table->float('price');
            $table->float('price_six');
            $table->float('price_year');
            $table->text('detail');
            $table->string('stripe_product_id');
            $table->string('stripe_price_id')->nullable();
            $table->string('stripe_price_year_id');
            $table->string('stripe_price_six_id')->nullable();
            $table->string('paypal_product_id');
            $table->string('paypal_plan_id')->nullable();
            $table->string('paypal_plan_six_id')->nullable();

            $table->string('paypal_plan_year_id');
            $table->string('free_video_1')->nullable();
            $table->string('free_video_2')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();

            $table->timestamps();
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('restrict');
        });

        Schema::create('package_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('package_id');
            $table->timestamp('subscribed_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->string('subscribed_status')->nullable();
            $table->boolean('renewal_status')->nullable();
            $table->string('subscription_id')->nullable();
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_by')->nullable();
            $table->string('billing_agreement_id')->nullable();
            $table->string('error_message')->nullable();
            $table->string('frequency')->nullable();
            $table->string('interval_count')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('restrict');
            $table->unique(['user_id','package_id']);
        });
        Schema::create('package_cant_delete', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('package_id');
            $table->timestamps();
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('packages');
    }
}
