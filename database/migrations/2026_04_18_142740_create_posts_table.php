<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
      Schema::create('posts', function (Blueprint $table) {
        $table->id(); // primary key

        $table->string('username'); // who posted
        $table->text('content'); // the post text
        $table->string('location')->nullable(); // optional location
        $table->string('image')->nullable(); // optional image path

        $table->timestamps(); // created_at & updated_at
      });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
