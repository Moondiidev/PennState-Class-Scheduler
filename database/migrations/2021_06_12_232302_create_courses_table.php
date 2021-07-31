<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->text('abbreviation');
            $table->integer('credits')->default(3);
            $table->text('description');
            $table->text('type');
            $table->json('prerequisites')->nullable();
            $table->json('concurrents')->nullable();
            $table->integer('prerequisites_for_count')->default(0);
            $table->boolean('semester_specific')->default(false);
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
        Schema::dropIfExists('courses');
    }
}
