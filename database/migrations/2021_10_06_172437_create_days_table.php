<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('days', function (Blueprint $table) {
            $table->id();
            $table->string('date');
            $table->float('remains_pre');
            $table->integer('odo_pre');
            $table->float('fuel');
            $table->integer('odo_post');
            $table->float('remains_post');
            $table->boolean('work_in_red');
            $table->boolean('is_trip');
            $table->boolean('red_day');
            $table->string('comment')->nullable();
			$table->foreignId('car_id')->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('days');
    }
}
