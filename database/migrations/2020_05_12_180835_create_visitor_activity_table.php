<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitorActivityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visitor_activity', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('route')->nullable();
            $table->mediumText('url_parameters');
            $table->string('activity_type');
            $table->string('campaign')->nullable();
            $table->string('ip_address')->nullable();

            $table->mediumText('misc_info')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->uuid('user_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('visitor_activity');
    }
}
