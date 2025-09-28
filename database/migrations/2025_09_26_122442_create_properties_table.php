<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // bailleur
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('address')->nullable();
            $table->integer('rooms')->nullable();
            $table->integer('price')->nullable();
            $table->string('city')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('properties');
    }
};
