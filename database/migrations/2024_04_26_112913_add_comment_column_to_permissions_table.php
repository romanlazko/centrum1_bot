<?php

use Database\Seeders\SuperDuperAdminSeeder;
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
        Schema::table('permissions', function (Blueprint $table) {
            $table->after('guard_name', function($table){
                $table->string('comment')->nullable();
            });
        });

        $seeder = new SuperDuperAdminSeeder();
        $seeder->call(SuperDuperAdminSeeder::class);
    }
};
