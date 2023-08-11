<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notes', function (Blueprint $table) {

            $table->increments('id');
            //id for note
            $table->unsignedBigInteger('user_id')->nullable();
            //userid on note
            $table->string('title')->nullable();
            //title of note
            $table->string('body')->nullable();
            //content inside 
            $table->string('remainder')->nullable();
            //remainder of note
            $table->boolean('pinned')->default(false);
            //if note is pinned or not
            $table->boolean('archieved')->default(false);
            //note is archived or not
            $table->boolean('deleted')->default(false);
            // if it is deleted
            $table->unsignedInteger('index');
            //index of note
            $table->timestamps();
            //making userid a foreign key with ref to users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
