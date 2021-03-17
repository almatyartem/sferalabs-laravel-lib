<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOnlyForTestsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('only_for_tests_users', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->timestamps();
        });

        Schema::create('only_for_tests_posts', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('unique_string_example')->unique();
            $table->string('string_example');
            $table->integer('int_example')->default(0);
            $table->enum('enum_example', ['one', 'two']);
            $table->timestamps();
        });

        DB::table('only_for_tests_users')->insert([
            'id' => 1,
            'email' => 'test@test.test'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('only_for_tests_users');
        Schema::dropIfExists('only_for_tests_posts');
    }
}
