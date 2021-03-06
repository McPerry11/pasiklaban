<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('users', function (Blueprint $table) {
      $table->increments('id');
      $table->string('reference_number')->unique();
      $table->string('email_address');
      $table->string('first_name');
      $table->string('middle_initial');
      $table->string('last_name');
      $table->string('strand');
      $table->string('parents_contact_number');
      $table->string('preferred_school');
      $table->string('preferred_program');
      $table->string('picture_filename')->nullable();
      $table->integer('winner')->default(0);
      $table->timestamp('logged_at')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('users');
  }
}
