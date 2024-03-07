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
        Schema::create('parsed_data', function (Blueprint $table) {
            $table->id();
            $table->string('section');
            $table->string('protocol');
            $table->string('type');
            $table->string('name');
            $table->string('description');
            $table->string('resource_location');
            $table->string('entityid')->nullable();
            $table->string('dynamic_registration')->nullable();
            $table->string('client_secret')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parsed_data');
    }
};
