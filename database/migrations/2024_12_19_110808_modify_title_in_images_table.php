<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('images', function (Blueprint $table) {
            // Set the title column to nullable or set a default value
            $table->string('title')->nullable()->change();
            // Or you could set a default value
            // $table->string('title')->default('Untitled')->change();
        });
    }
    
    public function down()
    {
        Schema::table('images', function (Blueprint $table) {
            // Revert the change if needed
            $table->string('title')->nullable(false)->change();
        });
    }
    
};
