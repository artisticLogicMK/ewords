<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContestantsTable extends Migration
{
    public function up()
    {
        Schema::create('contestants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug');
            $table->integer('votes')->default(0);
            $table->integer('age')->nullable();
            $table->string('location');
            $table->string('occupation')->nullable();
            $table->string('title_of_piece');
            $table->string('writing_experience');
            $table->text('discovery_story');
            $table->string('video_path');
            $table->string('picture_path');
            $table->integer('youtube')->default(0);
            $table->string('youtube_link')->nullable();
            $table->integer('prioritize')->default(0);
            $table->integer('inclusion')->default(00);
            $table->integer('deleted')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('contestants');
    }
}

