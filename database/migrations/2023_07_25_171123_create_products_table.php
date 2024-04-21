<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string("name_ar")->nullable();
            $table->string("name_en")->nullable();
            $table->text("short_description")->nullable();
            $table->text("description")->nullable();
            $table->foreignId("media_manager_id")->nullable()->constrained("media_managers");
            $table->foreignId("brand_id")->nullable()->constrained("brands");
            $table->foreignId("unit_id")->nullable()->constrained("units");
            $table->integer("sell_target")->nullable()->default(0);
            $table->boolean("published")->default(0);
            $table->boolean("has_variations")->default(0);
            //Discount
            $table->date("from_date")->nullable();
            $table->date("to_date")->nullable();
            $table->decimal("discount")->default(0);
            $table->boolean("percent")->default(0);
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
        Schema::dropIfExists('products');
    }
}
