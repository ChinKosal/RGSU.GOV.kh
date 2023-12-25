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
        Schema::table('committees', function (Blueprint $table) {
            if (!Schema::hasColumn('committees', 'category')) {
                $table->longText('category')->comment('[1,2,3]')->after('type');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('committees', function (Blueprint $table) {
            if (Schema::hasColumn('committees', 'category')) {
                $table->dropColumn('category');
            }
        });
    }
};
