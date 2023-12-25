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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->longText('title')->comment('{en: "English", km: "Khmer"}');
            $table->longText('slug')->comment('English only');
            $table->string('thumbnail')->nullable();
            $table->longText('content')->comment('{en: "English", km: "Khmer"}');
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
        Schema::dropIfExists('news');
    }
};
