<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
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
            $table->integer("code_counter")->default(0);
            $table->string("code")->nullable();
            $table->decimal("sub_total")->default(0);
            $table->decimal("tax")->default(0);
            $table->decimal("discount")->default(0);
            $table->decimal("shipping_charge")->default(0);
            $table->decimal("additional_discount")->default(0);
            $table->boolean("additional_discount_percent")->default(0);
            $table->decimal("grand_total")->default(0);
            $table->string("status")->nullable(); //pending,cancelled , etc
            $table->string("payment_status")->nullable(); //paid , unpaid etc
            $table->string("payment_method")->nullable(); //cash , online 
            $table->string("type")->nullable(); //regular,schedular
            $table->string("categorize")->nullable(); //pos or online order
            $table->foreignId("customer_id")->nullable()->constrained("customers");
            $table->foreignId("location_id")->nullable()->constrained("locations");
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
        Schema::dropIfExists('orders');
    }
}
