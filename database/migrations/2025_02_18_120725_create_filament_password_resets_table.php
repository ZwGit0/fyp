<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilamentPasswordResetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filament_password_resets', function (Blueprint $table) {
            $table->string('email', 191);  // `email` column (varchar(191), NOT NULL)
            $table->string('token', 191);  // `token` column (varchar(191), NOT NULL)
            $table->timestamp('created_at')->nullable();  // `created_at` column (timestamp, nullable)
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('filament_password_resets');
    }
}
