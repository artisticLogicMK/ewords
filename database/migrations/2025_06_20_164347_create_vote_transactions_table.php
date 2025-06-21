<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('vote_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contestant_id')->constrained()->onDelete('cascade');
            $table->integer('votes');
            $table->integer('prev_votes');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->unsignedBigInteger('amount'); // in kobo
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vote_transactions');
    }
};
