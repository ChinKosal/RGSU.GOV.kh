<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->longText('title')->nullable()->comment('{"km": "","en": ""}');
            $table->string('page');
            $table->longText('content')->nullable()->comment('{"km": "","en": ""}');
            $table->string('thumbnail')->nullable();
            $table->tinyInteger('status')->default(config('dummy.status.active.key'));
            $table->tinyInteger('user_id');
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
        Schema::dropIfExists('pages');
    }
};
