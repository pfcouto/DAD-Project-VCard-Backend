<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->string('phone_number', 9);
            $table->string('contact', 9);
            $table->string('name', 255);
            $table->primary(['phone_number', 'contact']);
            $table->foreign('phone_number')->references('phone_number')->on('vcards');
            $table->foreign('contact')->references('phone_number')->on('vcards');
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
        Schema::dropIfExists('contacts');
    }
}
