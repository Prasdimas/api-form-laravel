<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->dateTime('created_by')->nullable();
        $table->timestamp('created_at')->nullable();
        $table->dateTime('updated_by')->nullable();
        $table->timestamp('updated_at')->nullable();
        $table->dateTime('deleted_by')->nullable();
        $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobs');
    }
}
