<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdditionalDescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('additional_descriptions', function (Blueprint $table) {
            $table->id();
            $table->string('color')->nullable();
            $table->bigInteger('weight')->nullable();
            $table->string('material')->nullable();
            $table->string('size')->nullable();
            $table->string('for')->nullable();
            $table->string('company')->nullable();
            $table->date('expireDate')->nullable();
            $table->foreignId('product_id')->constrained();
            $table->string('countryOfMade')->nullable();
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
        Schema::dropIfExists('additional_descriptions');
    }
}
