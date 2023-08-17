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
        Schema::create('_labels__notes', function (Blueprint $table) {
            $table->string('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('note_id');
            $table->unsignedInteger('label_id');
            $table->timestamps();

            //linking with foriegn keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('note_id')->references('id')->on('notes')->onDelete('cascade');
            $table->foreign('label_id')->references('id')->on('labels')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_labels__notes');
    }
};
