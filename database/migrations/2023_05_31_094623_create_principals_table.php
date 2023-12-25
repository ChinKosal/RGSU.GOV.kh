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
        Schema::create('principals', function (Blueprint $table) {
            $table->id();
            $table->longText('title')->comment('{en: "English", km: "Khmer"}');
            $table->string('slug')->comment('english only');
            $table->string('type');
            $table->longText('category')->comment('[1,2,3]');
            $table->longText('content')->nullable()->comment('{en: "English", km: "Khmer"}');
            $table->string('file')->nullable();
            $table->tinyInteger('user_id');
            $table->tinyInteger('status')->default(config('dummy.status.active.key'));
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
        Schema::dropIfExists('principals');
    }
};
