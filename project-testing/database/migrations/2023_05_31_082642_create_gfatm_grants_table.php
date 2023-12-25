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
        Schema::create('gfatm_grants', function (Blueprint $table) {
            $table->id();
            $table->longText('title')->comment('{en: "English", km: "Khmer"}');
            $table->string('slug')->comment('english only');
            $table->longText('category')->comment('[1,2,3]');
            $table->longText('link')->nullable();
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
        Schema::dropIfExists('gfatm_grants');
    }
};
