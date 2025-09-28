<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('properties', function (Blueprint $table) {
            // $table->integer('rooms')->nullable(); // nb de chambres
            $table->integer('pieces')->nullable(); // nb de pièces (studio)
            $table->integer('bathrooms')->nullable();
            $table->boolean('internal_bathroom')->nullable(); // chambre étudiant
            $table->boolean('kitchen')->nullable();
            $table->boolean('furnished')->nullable();
            $table->boolean('balcony')->nullable();
            $table->boolean('parking')->nullable();
            $table->boolean('security')->nullable();
            $table->boolean('pool')->nullable();
            $table->boolean('dependency')->nullable(); // dépendance maison
            $table->integer('floor')->nullable();
            $table->integer('surface')->nullable(); // surface habitable
            $table->integer('land_surface')->nullable(); // surface terrain villa
            $table->boolean('internet')->nullable(); // chambre étudiant
            $table->boolean('water_included')->nullable();
            $table->boolean('electricity_included')->nullable();
        });
    }

    public function down() {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn([
                'rooms','pieces','bathrooms','internal_bathroom','kitchen','furnished',
                'balcony','parking','security','pool','dependency','floor',
                'surface','land_surface','internet','water_included','electricity_included'
            ]);
        });
    }
};
