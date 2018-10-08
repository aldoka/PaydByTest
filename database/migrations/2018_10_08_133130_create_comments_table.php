<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();

            $table->string('author_name', 64);
            $table->string('author_email', 64);
            $table->string('comment', 1000);
            $table->string('comment_substr', 191);

            $table->unique(['author_name', 'author_email', 'comment_substr']);

            $table->unsignedInteger('podcast_id');
            $table->foreign('podcast_id')
                ->references('id')->on('podcasts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
