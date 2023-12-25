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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->nullable();
            $table->longText('name')->comment('{en: "English", km: "Khmer"}');
            $table->string('slug')->comment('slug only support english');
            $table->string('type')->comment('in config/dummy.php file -> category -> type');
            $table->integer('ordering')->default(1);
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
        Schema::dropIfExists('categories');
    }
};
