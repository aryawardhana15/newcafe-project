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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('user_id');
            $table->integer('quantity');
            $table->text('address');
            $table->text('shipping_address');
            $table->decimal('total_price', 10, 2);
            $table->unsignedBigInteger('payment_id');
            $table->unsignedBigInteger('bank_id')->nullable();
            $table->unsignedBigInteger('note_id')->nullable();
            $table->unsignedBigInteger('status_id')->default(2); // Default to pending
            $table->string('transaction_doc')->nullable();
            $table->boolean('is_done')->default(false);
            $table->text('refusal_reason')->nullable();
            $table->boolean('coupon_used')->default(false);
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
            $table->foreign('bank_id')->references('id')->on('banks')->onDelete('set null');
            $table->foreign('note_id')->references('id')->on('notes')->onDelete('set null');
            $table->foreign('status_id')->references('id')->on('statuses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
