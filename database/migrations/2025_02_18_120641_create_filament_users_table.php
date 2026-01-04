<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilamentUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('filament_users', function (Blueprint $table) {
        $table->id();  
        $table->string('avatar', 191)->nullable();  
        $table->string('email', 191)->unique();  
        $table->tinyInteger('is_admin')->default(1);
        $table->string('name', 191);  
        $table->string('password', 191);  
        $table->json('roles')->nullable();  
        $table->string('remember_token', 100)->nullable();  
        $table->timestamps();  
    });
}

    public function down()
    {
        Schema::dropIfExists('filament_users');
    }

}
