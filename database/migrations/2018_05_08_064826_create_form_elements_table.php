<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormElementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_elements', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('type', ['p','input_hidden','input_text','select','textarea','button','submit','time','date']);
            $table->string('name');
            $table->string('label')->nullable();
            $table->string('value')->nullable();
            $table->text('attr')->nullable();
            $table->integer('form_id');
            $table->integer('order')->default(100);
            $table->boolean('required')->default('false');
            $table->boolean('first_show')->default('true');
            $table->boolean('in')->default('true'); //будет ли данный элемент учитываться для параметров через REQUEST
            $table->foreign('form_id')->references('id')->on('forms');
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
        Schema::dropIfExists('form_elements');
    }
}
