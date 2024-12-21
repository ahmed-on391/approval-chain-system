<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        if (Schema::hasTable('approval_chains')) {
            if (!Schema::hasColumn('approval_chains', 'position')) {
                Schema::table('approval_chains', function (Blueprint $table) {
                    $table->integer('position')->nullable()->default(1);
                });
            } else {
                Schema::table('approval_chains', function (Blueprint $table) {
                    DB::statement('ALTER TABLE approval_chains MODIFY position INTEGER DEFAULT 1 NULL');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('approval_chains', function (Blueprint $table) {
            $table->integer('position')->nullable(false)->change(); // استرجاع الحالة السابقة إذا لزم الأمر
        });
    }
};