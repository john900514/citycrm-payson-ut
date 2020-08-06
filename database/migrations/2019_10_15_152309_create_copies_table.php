<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCopiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('copy', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->uuid('department_id')->nullable();

            $table->string('page')->nullable();
            $table->string('name')->nullable();
            $table->string('title')->nullable();
            $table->mediumText('desc')->nullable();


            $table->boolean('active')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('copy');
    }
}
