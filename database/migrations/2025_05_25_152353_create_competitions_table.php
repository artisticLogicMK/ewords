<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('competitions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('cover')->default('/assets/trophy.png');
            $table->string('slug')->unique();
            $table->longText('content')->nullable(); // 100,000 characters
            $table->string('stage')->default('1st');
            $table->longText('past_winners_content')->nullable(); // 100,000 characters
            $table->string('winner')->nullable();
            $table->string('winner_pic')->nullable();
            $table->string('first_runner')->nullable();
            $table->string('first_runner_pic')->nullable();
            $table->string('second_runner')->nullable();
            $table->string('second_runner_pic')->nullable();
            $table->dateTime('registration_closes')->nullable();
            $table->dateTime('first_voting_starts')->nullable();
            $table->dateTime('first_voting_ends')->nullable();
            $table->dateTime('second_voting_starts')->nullable();
            $table->dateTime('second_voting_ends')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competitions');
    }
};