<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSkillSetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skill_sets', function (Blueprint $table) {
        $table->foreignId('candidate_id');
        $table->foreignId('skill_id');
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
        Schema::dropIfExists('skill_sets');
    }
}
