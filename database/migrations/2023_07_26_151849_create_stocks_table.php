<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->string("variation_name_ar")->nullable();
            $table->string("variation_name_en")->nullable();
            $table->foreignId("product_id")->nullable()->constrained("products")->cascadeOnDelete();
            $table->foreignId("location_id")->nullable()->constrained("locations");
            $table->decimal("price")->default(0)->nullable();
            $table->integer("stock")->default(0)->nullable();
            $table->string("sku")->nullable();
            $table->string("code")->nullable();
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
        Schema::dropIfExists('stocks');
    }
}
