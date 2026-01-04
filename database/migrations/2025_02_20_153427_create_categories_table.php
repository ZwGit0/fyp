<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();  // Auto-incrementing ID for the category
            $table->string('name');  // Name of the category (e.g., 'Men Shirts', 'Sport Shirts')
            $table->unsignedBigInteger('product_type_id'); // Associate category with product type
            $table->timestamps();  // Timestamps for created_at and updated_at

            $table->foreign('product_type_id')->references('id')->on('product_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
